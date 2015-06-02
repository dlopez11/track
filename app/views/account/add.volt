{% extends "templates/default.volt" %}
{% block header %}
    <script type="text/javascript">
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
          });
    </script>
{% endblock %}
{% block content %}
    <br />
    <br />
    <br />
    <h1><span class="glyphicon glyphicon-plus-sign"></span> Nueva Cuenta</h1>
    <hr />
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 wrap">
            <h3><span class="glyphicon glyphicon-list"></span> Datos de la cuenta</h3>
            <hr />
            {{accountForm.render('name')}}
            <br /><br />
            {{accountForm.render('nit')}}
            <br /><br />
            {{accountForm.render('address')}}
            <br /><br />
            {{accountForm.render('city')}}
            <br /><br />
            {{accountForm.render('phone')}}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 wrap">
            <h3><span class="glyphicon glyphicon-user"></span> Usuario administrador</h3>
            <hr />
            {{userForm.render('username')}}
            <br /><br />
            {{userForm.render('pass')}}
            <br /><br />
            {{userForm.render('pass2')}}
            <br /><br />
            {{userForm.render('email')}}
            <br /><br />
            {{userForm.render('name')}}
            <br /><br />
            {{userForm.render('lastname')}}
            <br /><br />
            {{userForm.render('address-user')}}
            <br /><br />
            {{userForm.render('phone-user')}}
        </div>
    </div>
    <hr />
    <div class="text-right wrap">
        <small style="margin-right: 20px;">*<em>Todos los campos son necesarios.</em></small>
        <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Cancelar">
            <span class="glyphicon glyphicon-remove"></span>
        </button>
        <button class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Guardar">
            <span class="glyphicon glyphicon-ok"></span>
        </button>
    </div>
    <br />
{% endblock %}