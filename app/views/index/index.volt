{% extends "templates/default.volt" %}
{% block header %}
    {{ javascript_include('library/highcharts-4.1.6/js/highcharts.js') }}
    {{ javascript_include('library/highcharts-4.1.6/js/modules/exporting.js') }}
    <script type="text/javascript">
        $(function () {
    
        });


    </script>

{% endblock %}
{% block content %}
    <div class="space"></div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            
        </div>
        
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div id="container" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
        </div>
        
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            
        </div>        
    </div>    
    
    
{% endblock %}