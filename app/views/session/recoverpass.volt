{% extends "templates/clean.volt" %}
{% block header %}
    {{ stylesheet_link('css/session-styles.css') }}
{% endblock %}
{% block content %}
    <form action="{{url('session/recoverpass')}}" method="POST" class="form-signin">
        <img src="{{url('')}}images/logo.png" height="135" />
        {{flashSession.output()}}
        <h2 class="form-signin-heading">Recuperar Contraseña</h2>
        
        <label for="company" class="sr-only">Email</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Email" required autofocus>
        <br>        
        <button class="btn btn-lg btn-primary btn-block" type="submit">Recuperar</button>
        <a href="/track/session/login" class="btn btn-lg btn-danger btn-block">Cancelar</a>
    </form>
{% endblock %}