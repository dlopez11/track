{% extends "templates/default.volt" %}
{% block header %}
    <script type="text/javascript">
        var csv = "{{url('client/import')}}";
    </script>
    {{ javascript_include('library/jquery/jquery-1.11.3.min.js') }}
    {{ javascript_include('js/importclient.js') }}
{% endblock %}
{% block content %}
    <div class="space"></div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h1>Importar clientes desde archivo .csv</h1>
            <hr />            
        </div>        
    </div>
        
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">            
            <form id="subida">
                <input type="file" id="csv" name="csv" />
                <br />
                <a href="{{url('client/index')}}" class="btn btn-default">Cancelar</a>
                <input type="submit" value="Importar" class="btn btn-success"/>                
                <div id="respuesta" />
            </form>
        </div>
    </div>
    
    <div class="clearfix"></div>
    <div class="space"></div>
    
{% endblock %}