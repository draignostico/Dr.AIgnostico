from flask_restx import Api
from flask import Blueprint
from app.api.services.endpoint import services_ns

api_bp = Blueprint("api", __name__)

api = Api(
    api_bp,
    title="API")

api.add_namespace(services_ns, path="/services")
