{% extends "templates/default.volt" %}
{% block header %}
    {{ javascript_include('js/dom-data-manager.js') }}
    {{ javascript_include('js/paginator.js') }}
    <script type="text/javascript">
        $(function() {
            var domManager = new DomManager();
            domManager.setContainer('container');
            var paginator = new Paginator();
            paginator.setUrl('{{url('visit/getrows')}}');
            paginator.setDOM(domManager);
            paginator.setContainerControls('pagination');
            paginator.load();
        });
        
        function initialize() {
            var mapProp = {
                center:new google.maps.LatLng(51.508742,-0.120850),
                zoom:5,
                mapTypeId:google.maps.MapTypeId.ROADMAP
            };
            var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
        }
        
        google.maps.event.addDomListener(window, 'load', initialize);
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
                    <select id="limit" class="form-control">
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
                    <select id="user" class="form-control">
                        <option value="0">Todos los usuarios</option>
                        {% for user in users%}
                             <option value="{{user.idUser}}">{{user.name}} {{user.lastName}}</option>
                        {% endfor %}
                    </select>    
                </div>
                <div class="form-group">
                    <label for="visittype">Tipo de visita</label>
                    <select id="visittype" class="form-control">
                        <option value="0">Todos las visitas</option>
                        {% for tvisit in tvisits%}
                             <option value="{{tvisit.idVisittype}}">{{tvisit.name}}</option>
                        {% endfor %}
                    </select>    
                </div>
                <div class="form-group">
                    <label for="client">Cliente</label>
                    <select id="client" class="form-control">
                        <option value="0">Todos ls clientes</option>
                        {% for client in clients%}
                             <option value="{{client.idClient}}">{{client.name}}</option>
                        {% endfor %}
                    </select>    
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
            <div id="googleMap" style="width:500px;height:380px;"></div>
        </div>    
    </div>
{% endblock %}