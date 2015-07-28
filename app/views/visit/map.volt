{% extends "templates/default.volt" %}
{% block header %}
    <script type="text/javascript">
        jQuery(function($) {
            // Asynchronously Load the map API 
            var script = document.createElement('script');
            script.src = "http://maps.googleapis.com/maps/api/js?sensor=false&callback=initialize";
            document.body.appendChild(script);
        });

        function initialize() {
            var marker;
            var map;
            var myLatlng = new google.maps.LatLng({{visit.v.latitude}},{{visit.v.longitude}});
            var mapProp = {
              center:myLatlng,
              zoom:14,
              mapTypeId:google.maps.MapTypeId.TERRAIN
            };
            
            map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
            var image = '{{url('images/marker.png')}}';
            marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                title: '{{visit.v.location}}',
                icon: image
            });
          }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>    
    </script>
{% endblock %}
{% block content %}
    
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-6">
                <h2><strong>Usuario: {{user.name}} {{user.lastName}}</strong></h2>
            </div>
            <div class="col-md-6" align="right">
                <h2><a href="{{url('index')}}" class="btn btn-default">Regresar</a></h2>
            </div>
        </div>        
    </div>
    <hr /> 
    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 wrap">
            <table class="table table-bordered" style="width: 80%;" align="rigth">                
                <tbody>
                    <tr>
                        <td>
                            <strong>Fecha:</strong>
                        </td>                            
                        <td>
                            <strong>desde</strong> {{date('d/M/Y H:i:s', visit.v.start)}} <br>
                            <strong>hasta</strong> {{date('d/M/Y H:i:s', visit.v.end)}} 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Tipo de Visita:</strong>
                        </td>
                        <td>
                            {{visit.visit}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Cliente:</strong>
                        </td>
                        <td>
                            {{visit.client}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Ubicación:</strong>
                        </td>
                        <td>
                            {{visit.v.location}}
                        </td>
                    </tr>
                </tbody>                
            </table>
        </div>
        
        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 wrap">
            <div id="googleMap" style="width:750px;height:400px;"></div>
        </div>
            
    </div>
    
    <div class="space"></div>
    
{% endblock %}