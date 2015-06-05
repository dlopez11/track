{% extends "templates/default.volt" %}
{% block header %}
    <script src="https://maps.googleapis.com/maps/api/js"  type="text/javascript"></script>
    <script type="text/javascript">
        var markers = new Array();
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
            
            console.log(markers);

            // Info Window Content
            var infoWindowContent = [
                ['<div class="info_content">' +
                '<h3>London Eye</h3>' +
                '<p>The London Eye is a giant Ferris wheel situated on the banks of the River Thames. The entire structure is 135 metres (443 ft) tall and the wheel has a diameter of 120 metres (394 ft).</p>' +        '</div>'],
                ['<div class="info_content">' +
                '<h3>Palace of Westminster</h3>' +
                '<p>The Palace of Westminster is the meeting place of the House of Commons and the House of Lords, the two houses of the Parliament of the United Kingdom. Commonly known as the Houses of Parliament after its tenants.</p>' +
                '</div>']
            ];

            // Display multiple markers on a map
            var infoWindow = new google.maps.InfoWindow(), marker, i;

            // Loop through our array of markers & place each one on the map  
            for( i = 0; i < markers.length; i++ ) {
                var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
                bounds.extend(position);
                marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    title: markers[i][0]
                });

                // Allow each marker to have an info window    
                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                        infoWindow.setContent(infoWindowContent[i][0]);
                        infoWindow.open(map, marker);
                    }
                })(marker, i));

                // Automatically center the map fitting all markers on the screen
                map.fitBounds(bounds);
            }
            // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
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
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 wrap">
            <h4>Usuario: <strong>{{user.name}} {{user.lastName}}</strong></h4>
        </div>
    </div>
    <div id="map_canvas" style="width:100%;height:380px;"></div>
    
{% endblock %}