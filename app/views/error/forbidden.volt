{% extends "templates/clean.volt" %}
{% block header %}
{% endblock %}
{% block content %}
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 wrap" style="text-align: center; margin-top: 1%;">
                <h1>Acceso denegado</h1>
                <h1>403</h1>
                <h3>No tiene los permisos necesarios para cargar el recurso solicitado</h3>
                <h1>
                    <a href="{{url('')}}">
                        Regresar al dashboard
                    </a>    
                </h1>
            </div>
        </div>   
    </div>   
{% endblock %}