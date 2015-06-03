{% extends "templates/clean.volt" %}
{% block header %}
{% endblock %}
{% block content %}
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 wrap" style="text-align: center; margin-top: 1%;">
                <h1>Recurso no encontrado</h1>
                <h1>404</h1>
                <h3>El recurso que intenta cargar no se encuentra en este servidor</h3>
                <h1>
                    <a href="{{url('')}}">
                        Regresar al dashboard
                    </a>    
                </h1>
            </div>
        </div>   
    </div>   
{% endblock %}