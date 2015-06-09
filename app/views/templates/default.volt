<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=1">
        <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>
        <link rel="shortcut icon" type="image/x-icon" href="{{url('')}}images/favicons/favicon48x48.ico">
        
        <!-- Always force latest IE rendering engine or request Chrome Frame -->
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        {{ get_title() }}
        {# Jquery#}
        {{ javascript_include('library/jquery/jquery-1.11.3.min.js') }}
        {{ stylesheet_link('css/styles.css') }}
        {# base de bootstrap#}
        {{ stylesheet_link('library/bootstrap-3.3.4/css/bootstrap.css') }}
        {{ javascript_include('library/bootstrap-3.3.4/js/bootstrap.min.js') }}
        
        <script type="text/javascript">
            var myBaseURL = '{{url('')}}';
        </script>
        {% block header %}<!-- custom header code -->{% endblock %}
    </head>
    <body>
        <div class="container">
            <div class="header clearfix">
                <nav>
                    <ul class="nav nav-pills pull-right">
                        {{ partial("partials/menu_partial") }}
                        {% if userEfective.enable %}
                            <li role="presentation" data-toggle="tooltip" data-placement="bottom" title="Regresar a la sesión original">
                                <a href="{{url('session/logoutsuperuser')}}"><span class="glyphicon glyphicon-log-out"></span></a>
                            </li>
                        {% endif %}
                        <li role="presentation" class="dropdown">
                            <a id="drop4" href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                                {{userData.name}} {{userData.lastName}}
                                <span class="caret"></span>
                            </a>
                            <ul id="menu1" class="dropdown-menu" role="menu" aria-labelledby="drop4">
                                <li role="presentation">
                                    <a role="menuitem" tabindex="-1" href="{{url('session/logout')}}">Cerrar sesión</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <a href="http://www.sigmamovil.com/" target="_blank">
                    <img src="{{url('')}}images/logo.png" height="70" />
                </a>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    {% block content %}<!-- custom content body code -->{% endblock %}
                </div>    
            </div>    

            <footer class="footer">
                <p style="float: left;">&copy; Sigma Engine 2015, Todos los derechos reservados</p>
                <div style="float: right;">
                    <a href="https://es-es.facebook.com/SigmaMovil" target="_blank" data-toggle="tooltip" data-placement="top" title="Síguenos en facebook">
                        <img src="images/social/facebook.png" />
                    </a>
                    <a href="https://twitter.com/SigmaMovil" target="_blank" data-toggle="tooltip" data-placement="top" title="Síguenos en twitter">
                        <img src="images/social/twitter.png" />
                    </a>
                    <a href="https://www.youtube.com/channel/UCC_-Dd4-718gwoCPux8AtwQ" target="_blank" data-toggle="tooltip" data-placement="top" title="Síguenos en youtube">
                        <img src="images/social/youtube.png" />
                    </a>
                    <a href="https://plus.google.com/+Sigmamovil/posts" target="_blank" data-toggle="tooltip" data-placement="top" title="Síguenos en google plus">
                        <img src="images/social/google+.png" />
                    </a>
                    <a href="https://www.linkedin.com/company/sigma-m-vil-s.a." target="_blank" data-toggle="tooltip" data-placement="top" title="Síguenos en linkedin">
                        <img src="images/social/linkedin.png" />
                    </a>    
                </div>    
            </footer>   
        </div>  
    </body>
</html>
