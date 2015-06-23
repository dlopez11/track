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
                    title: 'Total de visitas',
                    serie: 'Visitas totales',
                    data: data
                });
            });
            
            $.get("{{url('statistic/getdata')}}/line", function(data, status){
                createLineGraphic({
                    container: '#container-line',
                    title: 'Total de visitas diarias',
                    serie: 'Visitas diarias',
                    data: data
                });
            });
            
            createBarGraphic({
                container: '#column',
                title: 'Cantidad de visitas por Usuario',                
            });
        });
    </script>

{% endblock %}
{% block content %}
    <div class="space"></div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
            <div id="container-line" style="min-width: 310px; height: 400px; max-width: 800px; margin: 0 auto"></div>
        </div>
        
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <div id="pie" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
        </div>
        
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div id="column" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
        </div>        
    </div>    
    
    
{% endblock %}