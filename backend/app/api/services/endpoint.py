from http import HTTPStatus
from flask_restx import Resource, Namespace
from app.api.services.services import obterDados, gerarRespostasLLM, listarTodos, atualizarResposta, deletarRegistro
from app.api.services.dto import idParser, perguntaParser, retornoModel, retornoModelList

NAMESPACE = {
    "name":"Services",
    "validate":True,
    "description":"Criando métodos HTTP para exemplificação."}

services_ns = Namespace(**NAMESPACE)

services_ns.models[retornoModel.name] = retornoModel
services_ns.models[retornoModelList.name] = retornoModelList

@services_ns.route('/obtendo-dados-por-id')
class GetDados(Resource):
    """
        Endpoint para obter um registro por id
    """
    @services_ns.expect(idParser) 
    @services_ns.marshal_with(retornoModel) 
    @services_ns.response(int(HTTPStatus.OK), "Dados obtidos com sucesso.") 
    @services_ns.response(int(HTTPStatus.BAD_REQUEST), "Erro de validação.")
    @services_ns.response(int(HTTPStatus.INTERNAL_SERVER_ERROR), "Internal server error.")
    def get(self):
        args = idParser.parse_args()
        return obterDados(args['id']), 200
    
@services_ns.route('/obtendo-dados-todos')
class GetTodosDados(Resource):
    """
        Endpoint para obter registros armazenados no banco
    """
    @services_ns.marshal_with(retornoModelList)
    @services_ns.response(int(HTTPStatus.OK), "Dados obtidos com sucesso.")
    @services_ns.response(int(HTTPStatus.BAD_REQUEST), "Erro de validação.")
    @services_ns.response(int(HTTPStatus.INTERNAL_SERVER_ERROR), "Internal server error.")
    def get(self):
        return listarTodos(), 200

@services_ns.route('/respostas-llm')
class PerguntaLLM(Resource):
    """
        Endpoint para obter respostas da LLM e armazenar no banco
    """
    @services_ns.expect(perguntaParser)
    @services_ns.response(int(HTTPStatus.OK), "Dados registrados com sucesso.")
    @services_ns.response(int(HTTPStatus.BAD_REQUEST), "Erro de validação.")
    @services_ns.response(int(HTTPStatus.INTERNAL_SERVER_ERROR), "Internal server error.")
    def post(self):
        args = perguntaParser.parse_args()
        return gerarRespostasLLM(args['pergunta']), 201

@services_ns.route('/atualizar-resposta')
class AtualizarResposta(Resource):
    """
        Endpoint para editar respostas da LLM e armazenar no banco
    """
    @services_ns.expect(idParser)
    @services_ns.response(int(HTTPStatus.OK), "Dados atualizados com sucesso.")
    @services_ns.response(int(HTTPStatus.BAD_REQUEST), "Erro de validação.")
    @services_ns.response(int(HTTPStatus.INTERNAL_SERVER_ERROR), "Internal server error.")
    def put(self):
        args = idParser.parse_args()
        return atualizarResposta(args['id']), 200

@services_ns.route('/deletar-registro')
class DeletarRegistro(Resource):
    """
        Endpoint para deletar um registro armazenado no banco
    """
    @services_ns.expect(idParser)
    @services_ns.response(int(HTTPStatus.OK), "Dados excluídos com sucesso.")
    @services_ns.response(int(HTTPStatus.BAD_REQUEST), "Erro de validação.")
    @services_ns.response(int(HTTPStatus.INTERNAL_SERVER_ERROR), "Internal server error.")
    def delete(self):
        args = idParser.parse_args()
        return deletarRegistro(args['id']), 200