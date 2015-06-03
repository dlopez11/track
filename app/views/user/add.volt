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
    <h1>Nuevo Usuario</h1>
    <hr />
    <form action="{{url('user/add')}}/{{(account.idAccount)}}" method="post">
        <div class="row">            
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 wrap">                
                <hr />
                {{UserForm.render('username')}}
                <br /><br />
                {{UserForm.render('pass')}}
                <br /><br />
                {{UserForm.render('pass2')}}
                <br /><br />
                {{UserForm.render('email')}}
                <br /><br />
                {{UserForm.render('name-user')}}
                <br /><br />
                {{UserForm.render('lastname')}}
                <br /><br />
                {{UserForm.render('address-user')}}
                <br /><br />
                {{UserForm.render('phone-user')}}
            </div>
        </div>
        <hr />
        <div class="text-right wrap">
            <small style="margin-right: 20px;">*<em>Todos los campos son necesarios.</em></small>
            <a href="{{url('user/index')}}/{{(account.idAccount)}}" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Cancelar">
                <span class="glyphicon glyphicon-remove"></span>
            </a>
            <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Guardar">
                <span class="glyphicon glyphicon-ok"></span>
            </button>
        </div>
    </form>
    <br />
{% endblock %}