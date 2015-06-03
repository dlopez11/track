{% extends "templates/default.volt" %}
{% block header %}
{% endblock %}
{% block content %}
    <h2>Editar Cuenta: <em><strong>{{account_value.name}}</strong></em></h2>
    {{flashSession.output()}}
    <form action="{{url('account/edit')}}/{{account_value.idAccount}}" method="post">
        <div class="row form-horizontal">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 wrap">
                <h3>Datos de la cuenta</h3>
                <hr />
                <div class="form-group">
                    {{accountForm.render('name')}}
                </div>    
                <div class="form-group">
                    {{accountForm.render('nit')}}
                </div>    
                <div class="form-group">
                    {{accountForm.render('address')}}
                </div>
                <div class="form-group">
                    {{accountForm.render('state', {'class': 'form-control select2'})}}
                </div>
                <div class="form-group">
                    {{accountForm.render('city', {'class': 'form-control select2'})}}
                </div>    
                <div class="form-group">
                    {{accountForm.render('phone')}}
                </div>
                <div class="form-group ">
                    {{accountForm.render('status', {'id': 'toggle-one'})}}
                </div>
            </div>
        </div>
        <hr />
        <div class="text-right wrap">
            <small style="margin-right: 20px;">*<em>Todos los campos son necesarios.</em></small>
            <a href="{{url('account/index')}}" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Cancelar">
                <span class="glyphicon glyphicon-remove"></span>
            </a>
            <button type="submit" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Guardar">
                <span class="glyphicon glyphicon-ok"></span>
            </button>
        </div>
    </form>
    <br />
{% endblock %}