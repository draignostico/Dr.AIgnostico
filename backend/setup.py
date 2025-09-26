from setuptools import setup, find_packages
from pathlib import Path

API_NAME = "ApiLLM"
DESCRIPTION = "API em Python usando LLM e integração com MongoDB"
APP_ROOT = Path(__file__).parent
INSTALL_REQUIRES = [
    "flask"
    "flask-restx"
    "flask-cors"
    "flask-bcrypt"
    "pymongo"
    "langchain"
    "langchain-community"
    "langchain-groq"
    "sentence-transformers"
    "PyPDF2"
    "python-dotenv"
]

setup(
    name=API_NAME,
    description=DESCRIPTION,
    long_description_content_type="text/markdown",
    packages=find_packages(where="src"),
    python_requires=">=3.9",
    install_requires=INSTALL_REQUIRES,
)
