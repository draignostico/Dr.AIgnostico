from pymongo import MongoClient
from app.config import MONGO_CONSTRING
from datetime import datetime
from bson import ObjectId
from http import HTTPStatus
from flask import abort
from app.rag import criar_chain_rag
import traceback

mongo_cliente = MongoClient(MONGO_CONSTRING)
db = mongo_cliente['TestDataBase']
collection = db['Dados']

rag_chain = criar_chain_rag()

def gerarRespostasLLM(pergunta):
    try:
        if not pergunta or not pergunta.strip():
            return {"erro": "Pergunta vazia"}, 400
        if rag_chain is None:
            return {"erro": "RAG não carregado"}, 500
        output = rag_chain.invoke({"query": pergunta})
        if not output:
            return {"erro": "Resposta vazia do RAG"}, 500
        resposta = output.get("result") if isinstance(output, dict) else getattr(output, "result", None)
        if resposta is None:
            return {"erro": "Formato de resposta inesperado do RAG"}, 500
        fontes = []
        for doc in output.get("source_documents", []):
            fonte_info = f"{doc.metadata.get('categoria', '')}/{doc.metadata.get('arquivo', '')}"
            fontes.append(fonte_info)
        try:
            resultado = collection.insert_one({
                "data": datetime.now(),
                "pergunta": pergunta,
                "resposta": resposta,
                "fontes": fontes
            })
            return {"id": str(resultado.inserted_id), "resposta": resposta, "fontes": fontes}
        except Exception as mongo_error:
            return {"resposta": resposta, "fontes": fontes, "aviso": "Dados não salvos no MongoDB devido a erro de conexão"}
    except Exception as e:
        tb = traceback.format_exc()
        return {"erro": str(e), "detalhes": tb}, 500

def obterDados(id):
    documento = collection.find_one({"_id": ObjectId(id)})
    if documento is None:
        abort(HTTPStatus.BAD_REQUEST, "Documento não encontrado.")
    return {
        "id": str(documento["_id"]),
        "data": documento["data"].isoformat() if isinstance(documento["data"], datetime) else str(documento["data"]),
        "resposta": documento["resposta"]
    }

def listarTodos():
    documentos = collection.find().sort("data", -1)
    resultado = []
    for doc in documentos:
        resultado.append({
            "id": str(doc["_id"]),
            "data": doc["data"].isoformat() if isinstance(doc["data"], datetime) else str(doc["data"]),
            "pergunta": doc["pergunta"],
            "resposta": doc["resposta"]
        })
    return {"Dados": resultado}

def atualizarResposta(id):
    documento = collection.find_one({"_id": ObjectId(id)})
    if documento is None:
        abort(HTTPStatus.BAD_REQUEST, "Documento não encontrado.")
    pergunta = documento["pergunta"]
    nova_saida = rag_chain.invoke({"query": pergunta})
    novaResposta = nova_saida.get("result") if isinstance(nova_saida, dict) else getattr(nova_saida, "result", None)
    collection.update_one(
        {"_id": ObjectId(id)},
        {"$set": {"resposta": novaResposta, "data": datetime.now()}}
    )
    return {"Mensagem": "Resposta atualizada com sucesso."}

def deletarRegistro(id):
    resultado = collection.delete_one({"_id": ObjectId(id)})
    if resultado.deleted_count == 0:
        abort(HTTPStatus.BAD_REQUEST, "Documento não encontrado.")
    return {"Mensagem": "Registro deletado com sucesso."}
