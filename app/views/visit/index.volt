{% extends "templates/default.volt" %}
{% block header %}
    {# Classie #}
    {{ javascript_include('library/notification-styles/js/classie.min.js') }}
    {# Modernizr #}
    {{ javascript_include('library/notification-styles/js/modernizr.custom.js') }}
    {# Notifications #}
    {{ partial("partials/notifications_partial") }}
    {# Paginator #}
    {{ javascript_include('js/dom-data-manager.js') }}
    {{ javascript_include('js/paginator.js') }}
    
    {# Select 2 #}
    {{ javascript_include('library/select2/js/select2.min.js') }}
    {{ stylesheet_link('library/select2/css/select2.min.css') }}
    <script type="text/javascript">
        var url = '{{url('visit')}}';
        $(function() {
            $(".select2").select2();
            
            var domManager = new DomManager();
            domManager.setContainer('container');
            var paginator = new Paginator();
            paginator.setUrl('{{url('visit/getrows')}}');
            paginator.setUrlReport('{{url('report')}}');
            paginator.setDOM(domManager);
            paginator.setContainerControls('pagination');
            paginator.load();
            
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
{% endblock %}
{% block content %}
    <div class="space"></div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h2>Listado de visitas</h2>
            <hr />
        </div>        
    </div>    
    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="form-inline text-center">
                <div class="form-group">
                    <label for="limit">Registros</label>
                    <select id="limit" class="form-control select2">
                        <option value="1">1</option>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15" selected>15</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>    
                </div>
                <div class="form-group">
                    <label for="user">Usuario</label>
                    <select id="user" class="form-control select2">
                        <option value="0">Todos los usuarios</option>
                        {% for user in users%}
                             <option value="{{user.idUser}}">{{user.name}} {{user.lastName}}</option>
                        {% endfor %}
                    </select>    
                </div>
                <div class="form-group">
                    <label for="visittype">Tipo de visita</label>
                    <select id="visittype" class="form-control select2">
                        <option value="0">Todos las visitas</option>
                        {% for tvisit in tvisits%}
                             <option value="{{tvisit.idVisittype}}">{{tvisit.name}}</option>
                        {% endfor %}
                    </select>    
                </div>
                <div class="form-group">
                    <label for="client">Cliente</label>
                    <select id="client" class="form-control select2">
                        <option value="0">Todos los clientes</option>
                        {% for client in clients%}
                             <option value="{{client.idClient}}">{{client.name}}</option>
                        {% endfor %}
                    </select>    
                </div>
                <div class="form-group">
                    <button class="btn btn-sm btn-primary" id="refresher" data-toggle="tooltip" data-placement="top" title="Refrescar tabla"><span class="glyphicon glyphicon-refresh"></span></button>
                </div>      
                <div class="form-group">
                    <button class="btn btn-sm btn-default" id="filter-downloader" data-toggle="tooltip" data-placement="top" title="Descargar contenido en pantalla"><span class="glyphicon glyphicon-download-alt"></span></button>
                </div>    
                <div class="form-group">
                    <button class="btn btn-sm btn-default" id="downloader" data-toggle="tooltip" data-placement="top" title="Descargar desde el origen de los tiempos"><span class="glyphicon glyphicon-download"></span></button>
                </div>      
            </div>
        </div>    
    </div>    
    
    <div class="space"></div>
    <div class="clearfix"></div>
    <div class="space"></div>
    <div class="clearfix"></div>
    <div class="space"></div>
    <div class="clearfix"></div>
    <div class="space"></div>
    <div class="clearfix"></div>
    <div class="space"></div>
    <div class="clearfix"></div>
    <div class="space"></div>
    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div id="container"></div>
        </div>    
    </div>    
    
    <div class="space"></div>
    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div id="pagination" class="text-center"></div>
        </div>    
    </div>
{% endblock %}