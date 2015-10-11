{% extends "templates/default.volt" %}
{% block header %}
    
    {# moment #}
    {{ javascript_include('library/moment/moment.js') }}
    {{ javascript_include('library/moment/locales/es.js') }}
    
    {# datetimepicker #}
    {{ javascript_include('library/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js') }}
    {{ stylesheet_link('library/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css') }}
    
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
                pintarMapa(result)
            });
        }
        /**
         * Metodo encargado de pitnar con la API de Google, Recibe un Objecto Tipo Json el cual 
         * tiene la Localizacion, latitud y longitud
         * @author Dorian Lopez - Sigma Movil 11/10/2015
         * @returns var result
         */
        function pintarMapa(result){
            var map;
                var bounds = new google.maps.LatLngBounds();
                markers = new Array();
                infoWindowContent = new Array();
                var mapOptions = {
                    mapTypeId: 'terrain'
                };

                // Display a map on the page
                map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
                map.setTilt(45);

                for(var i = 0; i < result.length; i++){
                    var array = new Array();
                    var array2 = new Array();
                    array.push(result[i].location);
                    array.push(result[i].latitude);
                    array.push(result[i].longitude);
                    array2.push(result[i].client);
                    markers.push(array);
                    infoWindowContent.push(array2);
                };


                //console.log(markers);
                //console.log(infoWindowContent);

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
                    this.setZoom(13);
                    google.maps.event.removeListener(boundsListener);
                });
        }
        $(function() {                        
            $('#date1').datetimepicker({
                format: "DD/MM/YYYY",
                showTodayButton: true,
                useCurrent: true
            });
            
            $('#date2').datetimepicker({
                format: "DD/MM/YYYY",
                showTodayButton: true,
                useCurrent: true
            });                       
            
            $('[data-toggle="tooltip"]').tooltip();
        });
        
        function clearDateTimePickers() {
            $('#date1').data("DateTimePicker").clear();
            $('#date2').data("DateTimePicker").clear();
        }
        
        function refreshMap(){
            document.getElementById("map_canvas").innerHTML = "";
            var s = $('#start').val();
            var start = s.split("/");            
            var dayone = moment(start[2] + '-' + start[1] + '-' + start[0] + ' 00:00:00').unix();
            
            var e = $('#end').val();                        
            var end = e.split("/");
            var daytwo = moment(end[2] + '-' + end[1] + '-' + end[0] + ' 00:00:00').unix();
                        
            $.ajax({
                url: '{{url('visit/getmapByRangeDate/')}}{{user.idUser}}',
                type: 'POST',
                data:({
                    start: dayone,
                    end: daytwo
                }),
                success:function(results) {
                   pintarMapa(results);
                }
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
    
    {#<div class="text-right">
        <a href="{{url('index')}}" class="btn btn-default" >Regresar</a>
    </div>#}
    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="form-inline text-center">
                <div class="form-group">
                    <button class="btn btn-sm btn-info" onClick="clearDateTimePickers();" data-toggle="tooltip" data-placement="top" title="Limpiar calendarios">
                        <span class="glyphicon glyphicon-erase"></span>
                    </button>
                </div>  
                
                <div class="form-group">
                    <div class="input-group date" id="date1">
                        <input type='text' class="form-control" id="start" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>  
                
                <div class="form-group">
                    <div class="input-group date" id="date2">
                        <input type='text' class="form-control" id="end"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>    
                
                <div class="form-group">
                    <button class="btn btn-sm btn-primary" onClick="refreshMap();" id="refresh" data-toggle="tooltip" data-placement="top" title="Refrescar tabla">
                        <span class="glyphicon glyphicon-refresh"></span>
                    </button>
                </div>
            </div>    
        </div>    
    </div>
    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 wrap">
            <h4>Usuario: <strong>{{user.name}} {{user.lastName}}</strong></h4>
        </div>
    </div>
    <div id="map_canvas" style="width:100%;height:380px;"></div>
    <br />
{% endblock %}