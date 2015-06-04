{% extends "templates/default.volt" %}
{% block header %}
{% endblock %}
{% block content %}
    <h2>Editar Cuenta: <em><strong>{{account_value.name}}</strong></em></h2>
    {{flashSession.output()}}
    <form action="{{url('account/edit')}}/{{account_value.idAccount}}" method="post">
        <div class="row form-horizontal">
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 wrap"></div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 wrap">
                <div class="space"></div>
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
                    {{accountForm.render('state', {'class': 'form-control select2', 'id':'state'})}}
                </div>
                <div class="form-group">
                    <select id="city" name="city" class="form-control select2">
                        <option value="{{account_value.city}}">{{account_value.city}}</option>
                    </select>
                </div>    
                <div class="form-group">
                    {{accountForm.render('phone')}}
                </div>
                <div class="form-group ">
                    {{accountForm.render('status', {'id': 'toggle-one'})}}
                </div>
                <div class="text-right wrap">
                    <small style="margin-right: 20px;">*<em>Todos los campos son necesarios.</em></small>
                    <a href="{{url('account/index')}}" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Cancelar">
                        <span class="glyphicon glyphicon-remove"></span>
                    </a>
                    <button type="submit" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Guardar">
                        <span class="glyphicon glyphicon-ok"></span>
                    </button>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 wrap"></div>
        </div>
    </form>
    <br />
{% endblock %}