import os
from pathlib import Path
from PyPDF2 import PdfReader
from langchain.text_splitter import RecursiveCharacterTextSplitter
from langchain_community.embeddings import HuggingFaceEmbeddings
from langchain_community.vectorstores import FAISS
from langchain.schema import Document

# Configuração
BASE_DIR = Path(__file__).resolve().parent
DADOS_DIR = BASE_DIR / "dados"

print(" Procurando PDFs na pasta 'dados'...")

def carregar_e_vetorizar_pdfs():
    """Passo 1: Carregar todos os PDFs"""
    documentos = []
    
    # Verificar se a pasta dados existe
    if not DADOS_DIR.exists():
        print(" ERRO: Pasta 'dados' não encontrada!")
        return None
    
    # Procurar PDFs
    pdf_count = 0
    for pasta in DADOS_DIR.iterdir():
        if pasta.is_dir():
            for arquivo in pasta.iterdir():
                if arquivo.suffix == ".pdf":
                    pdf_count += 1
                    print(f" Encontrado: {pasta.name}/{arquivo.name}")
    
    print(f" Total de PDFs encontrados: {pdf_count}")
    
    if pdf_count == 0:
        print("❌ Nenhum PDF encontrado! Verifique a pasta 'dados'")
        return None
    
    # Divisor de texto - CRUCIAL para PDFs
    text_splitter = RecursiveCharacterTextSplitter(
        chunk_size=800,      # Tamanho de cada pedaço
        chunk_overlap=150,   # Sobreposição para manter contexto
        length_function=len
    )
    
    # Passo 2: Ler cada PDF e dividir em pedaços
    print("\n Lendo e dividindo PDFs...")
    for pasta in DADOS_DIR.iterdir():
        if pasta.is_dir():
            for arquivo in pasta.iterdir():
                if arquivo.suffix == ".pdf":
                    try:
                        print(f"   Processando: {arquivo.name}")
                        
                        # Ler PDF
                        reader = PdfReader(arquivo)
                        texto_completo = ""
                        
                        for pagina in reader.pages:
                            texto_pagina = pagina.extract_text() or ""
                            texto_completo += texto_pagina + "\n"
                        
                        # Dividir em pedaços (chunks)
                        chunks = text_splitter.split_text(texto_completo)
                        
                        # Criar documentos para cada chunk
                        for i, chunk in enumerate(chunks):
                            documentos.append(Document(
                                page_content=chunk,
                                metadata={
                                    "categoria": pasta.name,
                                    "arquivo": arquivo.name,
                                    "fonte": f"{pasta.name}/{arquivo.name}",
                                    "chunk_numero": i + 1
                                }
                            ))
                            
                    except Exception as e:
                        print(f" Erro em {arquivo.name}: {e}")
    
    print(f" Total de pedaços (chunks) criados: {len(documentos)}")
    
    # Passo 3: VETORIZAÇÃO (Transformar texto em números)
    print("\n Vetorizando textos...")
    try:
        # Modelo que transforma texto em números
        embeddings = HuggingFaceEmbeddings(
            model_name="all-MiniLM-L6-v2",
            model_kwargs={'device': 'cpu'}
        )
        
        # Criar banco de vetores
        vectorstore = FAISS.from_documents(documentos, embeddings)
        
        # Salvar para usar depois
        vectorstore.save_local("vetores_doencas")
        print(" Vetores salvos em 'vetores_doencas'")
        
        return vectorstore
        
    except Exception as e:
        print(f" Erro na vetorização: {e}")
        return None

if __name__ == "__main__":
    print(" INICIANDO VETORIZAÇÃO DOS PDFs")
    print("=" * 50)
    
    vetorizador = carregar_e_vetorizar_pdfs()
    
    if vetorizador:
        print("\n VETORIZAÇÃO CONCLUÍDA COM SUCESSO!")
        print(" Estatísticas:")
        print(f"   - PDFs processados: {len([f for f in DADOS_DIR.rglob('*.pdf')])}")
        print(f"   - Chunks criados: {vetorizador.index.ntotal}")
    else:
        print("\n Falha na vetorização. Verifique os erros acima.")