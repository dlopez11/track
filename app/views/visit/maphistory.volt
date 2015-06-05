{% extends "templates/default.volt" %}
{% block header %}
    <script type="text/javascript">
        var markers = new Array();
        var infoWindowContent = new Array();
        jQuery(function($) {
            // Asynchronously Load the map API 
            var script = document.createElement('script');
            script.src = "http://maps.googleapis.com/maps/api/js?sensor=false&callback=initialize";
            document.body.appendChild(script);
        });

        function initialize() {
            $.getJSON("{{url('visit/getmap/')}}{{user.idUser}}", function (result){
            var map;
            var bounds = new google.maps.LatLngBounds();
            var mapOptions = {
                mapTypeId: 'roadmap'
            };

            // Display a map on the page
            map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
            map.setTilt(45);
            
            for(var i = 0; i < result.length; i++){
                var array = new Array();
                array.push(result[i].location);
                array.push(result[i].latitude);
                array.push(result[i].longitude);
                markers.push(array);
            };
            
            for(var ii = 0; ii < result.length; ii++){
                var array2 = new Array();
                array2.push(result[ii].client);
                infoWindowContent.push(array2);
            };
            
            console.log(markers);
            console.log(infoWindowContent);

            var infoWindow = new google.maps.InfoWindow(), marker, i;
 
            var image = '{{url('images/marker.png')}}';
            for( i = 0; i < markers.length; i++ ) {
                var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
                bounds.extend(position);
                marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    title: markers[i][0],
                    icon: image
                });

                // Allow each marker to have an info window    
                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                        infoWindow.setContent(infoWindowContent[i][0]);
                        infoWindow.open(map, marker);
                    }
                })(marker, i));

                map.fitBounds(bounds);
            }
            
            var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
                this.setZoom(12);
                google.maps.event.removeListener(boundsListener);
            });
});
        }
    </script>
    
{% endblock %}
{% block content %}
    <div class="row">
        <div class="col-md-12">
            <h2>Historial de visitas</h2>
            <hr />
        </div>
    </div>
    {{flashSession.output()}}
    
    <div class="text-right">
        <a href="{{url('index')}}" class="btn btn-default" >Regresar</a>
    </div>
    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 wrap">
            <h4>Usuario: <strong>{{user.name}} {{user.lastName}}</strong></h4>
        </div>
    </div>
    <div id="map_canvas" style="width:100%;height:380px;"></div>
    <br />
{% endblock %}