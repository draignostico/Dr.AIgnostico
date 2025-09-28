from flask import Flask, request, jsonify
from flask_cors import CORS
from app.api.services.services import gerarRespostasLLM
from langchain_groq import ChatGroq
import json
import re
import os
from dotenv import load_dotenv

load_dotenv()

app = Flask(__name__)
CORS(app, resources={r"/*": {"origins": "*"}})

@app.route("/respostas-llm", methods=["POST"])
def respostas_llm():
    try:
        data = request.get_json()
        pergunta = data.get("pergunta", "")
        if not pergunta or not pergunta.strip():
            return jsonify({"erro": "Pergunta não pode ser vazia"}), 400
        resultado = gerarRespostasLLM(pergunta)
        if isinstance(resultado, tuple):
            return jsonify(resultado[0]), resultado[1]
        return jsonify(resultado), 200
    except Exception as e:
        return jsonify({"erro": str(e)}), 500

@app.route("/doenca/<nome>", methods=["GET"])
def get_doenca(nome):
    try:
        api_key = os.getenv("GROQ_API_KEY")
        llm = ChatGroq(model="llama-3.1-8b-instant", temperature=0.2, api_key=api_key)
        prompt = f"""
Você é um sistema de apoio médico.
Responda APENAS em JSON válido, sem explicações extras.

Sobre a doença "{nome}", retorne exatamente neste formato:
{{
  "descricao": "texto breve explicando a doença",
  "sintomas": ["sintoma1", "sintoma2", "sintoma3"],
  "tratamentos": ["tratamento1", "tratamento2", "tratamento3"],
  "aviso": "aviso importante ao paciente"
}}
"""
        resposta = llm.invoke(prompt)
        conteudo = getattr(resposta, "content", str(resposta)).strip()
        match = re.search(r"\{.*\}", conteudo, re.DOTALL)
        if match:
            conteudo = match.group(0)
        try:
            dados = json.loads(conteudo)
        except Exception:
            dados = {"descricao": conteudo, "sintomas": [], "tratamentos": [], "aviso": ""}
        dados = {
            "descricao": dados.get("descricao", ""),
            "sintomas": dados.get("sintomas", []),
            "tratamentos": dados.get("tratamentos", []),
            "aviso": dados.get("aviso", "")
        }
        return jsonify(dados), 200
    except Exception as e:
        return jsonify({"erro": str(e)}), 500

if __name__ == "__main__":
    app.run(host="127.0.0.1", port=8000, debug=True)
