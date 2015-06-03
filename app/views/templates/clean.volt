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
            {% block content %}<!-- custom content body code -->{% endblock %}
        </div>
    </body>
</html>
