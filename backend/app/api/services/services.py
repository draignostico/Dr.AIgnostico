from pymongo import MongoClient
from app.config import MONGO_CONSTRING
from datetime import datetime
from bson import ObjectId
from http import HTTPStatus
from flask import abort
from app.rag import criar_chain_rag


# primeiro abrimos conexão com o MongoDB
mongo_cliente = MongoClient(MONGO_CONSTRING)
db = mongo_cliente['TestDataBase']
collection = db['Dados']

# depois carregamos a chain de RAG uma única vez (ela já vem com o LLM dentro)
rag_chain = criar_chain_rag()


def gerarRespostasLLM(pergunta):
    """
    fluxo:
    - recebe a pergunta do usuário
    - usa a chain RAG para buscar resposta nos vetores
    - coleta também as fontes que foram utilizadas
    - tenta salvar a pergunta, resposta e fontes no MongoDB
    - retorna os dados (id, resposta e fontes)
    """
    try:
        output = rag_chain.invoke({"query": pergunta})
        resposta = output["result"]

        fontes = []
        for doc in output["source_documents"]:
            fonte_info = f"{doc.metadata.get('categoria', '')}/{doc.metadata.get('arquivo', '')}"
            fontes.append(fonte_info)

        try:
            resultado = collection.insert_one({
                "data": datetime.now(),
                "pergunta": pergunta,
                "resposta": resposta,
                "fontes": fontes
            })
            return {
                "id": str(resultado.inserted_id),
                "resposta": resposta,
                "fontes": fontes
            }
        except Exception as mongo_error:
            print(f"Erro ao salvar no MongoDB: {mongo_error}")
            return {
                "resposta": resposta,
                "fontes": fontes,
                "aviso": "Dados não salvos no MongoDB devido a erro de conexão"
            }

    except Exception as e:
        print("[ERRO gerarRespostasLLM]", str(e))
        return {"erro": str(e)}, 500


def obterDados(id):
    """
    fluxo:
    - procura um documento específico pelo ID
    - se não encontrar, retorna erro 400
    - se encontrar, retorna os campos principais (id, data e resposta)
    """
    documento = collection.find_one({"_id": ObjectId(id)})
    if documento is None:
        abort(HTTPStatus.BAD_REQUEST, "Documento não encontrado.")

    return {
        "id": str(documento["_id"]),
        "data": documento["data"].isoformat() if isinstance(documento["data"], datetime) else str(documento["data"]),
        "resposta": documento["resposta"]
    }


def listarTodos():
    """
    fluxo:
    - busca todos os documentos no MongoDB
    - ordena do mais recente para o mais antigo
    - retorna uma lista com id, data, pergunta e resposta
    """
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
    """
    fluxo:
    - busca o documento no MongoDB
    - se não existir, retorna erro
    - pega a pergunta armazenada
    - gera uma nova resposta usando o mesmo RAG
    - atualiza a resposta e a data no banco
    - confirma atualização
    """
    documento = collection.find_one({"_id": ObjectId(id)})
    if documento is None:
        abort(HTTPStatus.BAD_REQUEST, "Documento não encontrado.")

    pergunta = documento["pergunta"]

    nova_saida = rag_chain.invoke({"query": pergunta})
    novaResposta = nova_saida["result"]

    collection.update_one(
        {"_id": ObjectId(id)},
        {"$set": {"resposta": novaResposta, "data": datetime.now()}}
    )

    return {"Mensagem": "Resposta atualizada com sucesso."}


def deletarRegistro(id):
    """
    fluxo:
    - tenta deletar o documento pelo ID
    - se não encontrar nada, retorna erro
    - caso contrário, confirma exclusão
    """
    resultado = collection.delete_one({"_id": ObjectId(id)})
    if resultado.deleted_count == 0:
        abort(HTTPStatus.BAD_REQUEST, "Documento não encontrado.")
    return {"Mensagem": "Registro deletado com sucesso."}