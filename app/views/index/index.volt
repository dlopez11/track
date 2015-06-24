{% extends "templates/default.volt" %}
{% block header %}
    {{ javascript_include('library/highcharts-4.1.6/js/highcharts.js') }}
    {{ javascript_include('library/highcharts-4.1.6/js/modules/exporting.js') }}
    {{ javascript_include('js/highcharts.js') }}
    <script type="text/javascript">
        $(function () {
            $.get("{{url('statistic/getdata')}}/pie", function(data, status){
                createPie({
                    container: '#pie',
                    title: "<strong>Total de visitas</strong>",
                    serie: 'Visitas totales',
                    data: data
                });
            });
            
            $.get("{{url('statistic/getdata')}}/line", function(r, status){
                console.log(r[0].data);
                createLineGraphic({
                    container: '#container-line',
                    title: '<strong>Visitas diarias</strong>',
                    categories: r[0].categories,
                    data: r[0].data
                });
            });
            
            
            $.get("{{url('statistic/getdata')}}/column", function(response, status){
                createBarGraphic({
                    container: '#column',
                    title: '<strong>Cantidad de visitas por Usuario</strong>',
                    yAxis: 'Número de visitas',
                    categories: response[0].time,
                    data: response[0].data
                });
            });
            
            
        });
    </script>

{% endblock %}
{% block content %}
    <div class="space"></div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 graphic-container">
            <div id="pie" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
        </div>
        
        
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 graphic-container">
            <div id="container-line"></div>
        </div>    
        
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 graphic-container">
            <div id="column"></div>
        </div>        
    </div>    
    
    
{% endblock %}