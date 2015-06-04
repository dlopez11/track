{% extends "templates/default.volt" %}
{% block header %}        
    <script type="text/javascript">        
        function initialize() {
            var mapProp = {
              center:new google.maps.LatLng(51.508742,-0.120850),
              zoom:8,
              mapTypeId:google.maps.MapTypeId.ROADMAP
            };
            var map=new google.maps.Map(document.getElementById("googleMap"), mapProp);
          }
          google.maps.event.addDomListener(window, 'load', initialize);        
    </script>
{% endblock %}
{% block content %}
    <div class="row">
        <div class="col-md-12">
            <h2><strong>Usuario: {{user.name}} {{user.lastName}}</strong></h2>
            <hr />
        </div>
    </div>
    
    <div class="space"></div>   
    <div class="text-right">
        <a href="{{url('index')}}" class="btn btn-default">Regresar</a>
    </div>
    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 wrap">
            <table class="table table-bordered" style="width: 45%;" align="rigth">                
                <tbody>
                    <tr>
                        <td>
                            <strong>Fecha:</strong>
                        </td>
                        <td>
                            
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Tipo de Visita:</strong>
                        </td>
                        <td>
                            
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Cliente:</strong>
                        </td>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Ubicación:</strong>
                        </td>
                        <td>
                        </td>
                    </tr>
                </tbody>                
            </table>
        </div>                            
    </div>
        
    <div id="googleMap" style="width:500px;height:380px;"></div>    
    
    <div class="space"></div>
    
{% endblock %}