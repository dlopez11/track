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
            var myLatlng = new google.maps.LatLng({{visit.latitude}},{{visit.longitude}});
            var mapProp = {
              center:myLatlng,
              zoom:14,
              mapTypeId:google.maps.MapTypeId.ROADMAP
            };
            
            map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
            var image = '{{url('images/marker.png')}}';
            marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                title: '{{visit.location}}',
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
            <h2><strong>Usuario: {{user.name}} {{user.lastName}}</strong></h2>                        
        </div>        
    </div>
    
    <div class="text-right">
        <a href="{{url('index')}}" class="btn btn-default" >Regresar</a>
        <hr />
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
                           {{date('d/m/Y g:i a', visit.date)}} 
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
                            {{visit.location}}
                        </td>
                    </tr>
                </tbody>                
            </table>
        </div>
        
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 wrap">
            <div id="googleMap" style="width:500px;height:380px;"></div>
        </div>
            
    </div>
    
    <div class="space"></div>
    
{% endblock %}