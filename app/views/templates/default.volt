<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=1">
        <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>
        <!-- Always force latest IE rendering engine or request Chrome Frame -->
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        {{ get_title() }}
        {# Jquery#}
        {{ javascript_include('library/jquery/jquery-1.11.3.min.js') }}
        {{ stylesheet_link('css/styles.css') }}
        {# base de bootstrap#}
        {{ stylesheet_link('library/bootstrap-3.3.4/css/bootstrap.css') }}
        {{ javascript_include('library/bootstrap-3.3.4/js/bootstrap.min.js') }}

        {{ stylesheet_link('css/adjustments.css') }}
        <script type="text/javascript">
            var myBaseURL = '{{url('')}}';
        </script>
        {% block header %}<!-- custom header code -->{% endblock %}
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="header clearfix">
                        <nav>
                            {{ partial("partials/menu_partial") }}
                        </nav>
                        <h3 class="text-muted">Sigma Móvil Track</h3>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            {% block content %}<!-- custom content body code -->{% endblock %}
                        </div>    
                    </div>    

                    <footer class="footer">
                        <p>&copy; Sigma Engine 2015, Todos los derechos reservados</p>
                    </footer>    
                </div>    
            </div>
        </div>
    </body>
</html>
