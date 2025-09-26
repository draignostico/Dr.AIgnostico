
from flask_restx import Model, fields
from flask_restx.reqparse import RequestParser

idParser = RequestParser(bundle_errors=True)
idParser.add_argument(name='id', type=str, required=True)

perguntaParser = RequestParser(bundle_errors=True)
perguntaParser.add_argument(name='pergunta', type=str, required=True)

retornoModel = Model('Retorno', {
    'id': fields.String,
    'data': fields.String,
    'pergunta': fields.String,
    'resposta': fields.String
})

retornoModelList = Model('RetornoList', {
    'Dados': fields.List(fields.Nested(retornoModel))
})