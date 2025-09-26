import os
import json
from pathlib import Path
from dotenv import load_dotenv
from langchain_community.vectorstores import FAISS
from langchain_huggingface import HuggingFaceEmbeddings
from langchain_groq import ChatGroq
from langchain.chains import RetrievalQA
from langchain.prompts import PromptTemplate

# carrega variáveis de ambiente
load_dotenv()

BASE_DIR = Path(__file__).resolve().parent.parent
DADOS_DIR = BASE_DIR / "dados"

if not DADOS_DIR.exists():
    raise FileNotFoundError(f"Pasta 'dados' não encontrada em {DADOS_DIR}")


def criar_chain_rag():
    try:
        embeddings = HuggingFaceEmbeddings(model_name="all-MiniLM-L6-v2")

        vectorstore = FAISS.load_local(
            "vetores_doencas",
            embeddings,
            allow_dangerous_deserialization=True
        )
        print("Vetores carregados com sucesso!")

        retriever = vectorstore.as_retriever(
            search_type="similarity",
            search_kwargs={"k": 5}
        )

        llm = ChatGroq(
            model="llama-3.1-8b-instant",
            temperature=0,
            api_key=os.getenv("GROQ_API_KEY")  # pega a chave direto do .env
        )

        prompt_template = """
        Você é um sistema de apoio ao diagnóstico médico.
        Com base nos documentos fornecidos e nos sintomas descritos pelo usuário,
        gere uma lista de até 5 doenças que mais se relacionam com esses sintomas.

        Responda **apenas com os nomes das doenças**, um por linha, sem explicações adicionais, sem títulos e sem texto extra.

        Sintomas: {question}

        Documentos relevantes:
        {context}
        """

        prompt = PromptTemplate(
            input_variables=["context", "question"],
            template=prompt_template
        )

        return RetrievalQA.from_chain_type(
            llm=llm,
            retriever=retriever,
            return_source_documents=True,
            chain_type_kwargs={"prompt": prompt}
        )

    except Exception as e:
        print(f"Erro ao carregar vetores: {e}")
        print("Execute primeiro: python vetorizador.py")
        return None