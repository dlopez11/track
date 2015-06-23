{% extends "templates/clean.volt" %}
{% block header %}
    {{ stylesheet_link('css/session-styles.css') }}
{% endblock %}
{% block content %}
    <form action="{{url('session/setnewpass')}}" method="POST" class="form-signin">
        <input type="hidden" name="uniq" value="{{uniq}}"/>
        <img src="{{url('')}}images/logo.png" height="135" />
        {{flashSession.output()}}
        <h2 class="form-signin-heading">Cambiar la contraseña del Usuario</h2>
        
        <label for="company" class="sr-only">*Contraseña</label>
        <input type="password" id="pass1" name="pass1" class="form-control" placeholder="Contraseña" required autofocus>
        <label for="company" class="sr-only">*Repita la contraseña</label>
        <input type="password" id="pass2" name="pass2" class="form-control" placeholder="Repita la contraseña" required autofocus>
        <br>        
        <button class="btn btn-lg btn-primary btn-block" type="submit">Cambiar contraseña</button>
        <a href="/track/session/login" class="btn btn-lg btn-danger btn-block">Cancelar</a>
    </form>
{% endblock %}