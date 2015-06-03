{% extends "templates/default.volt" %}
{% block header %}
{% endblock %}
{% block content %}
    <div class="row">
        <div class="col-md-12">
            <h2>Nueva Cuenta</h2>
            <hr />
        </div>
    </div>    
    
    <div class="space"></div>
    
    <div class="row">
        <div class="col-md-12">
            {{flashSession.output()}}
            
            <form action="{{url('account/add')}}" method="post">
                <div class="row form-horizontal wrap">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 wrap">
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
                            Estado:<br />
                            {{accountForm.render('status', {'id': 'toggle-one'})}}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 wrap">
                        <h3>Usuario administrador</h3>
                        <hr />
                        <div class="form-group">
                            {{userForm.render('userName')}}
                        </div>
                        <div class="form-group">
                            {{userForm.render('pass')}}
                        </div>
                        <div class="form-group">
                            {{userForm.render('pass2')}}
                        </div>
                        <div class="form-group">
                            {{userForm.render('email')}}
                        </div>
                        <div class="form-group">
                            {{userForm.render('name-user')}}
                        </div>
                        <div class="form-group">
                            {{userForm.render('lastName')}}
                        </div>
                        <div class="form-group">
                            {{userForm.render('address-user')}}
                        </div>
                        <div class="form-group">
                            {{userForm.render('state-user', {'class': 'form-control select2'})}}
                        </div>   
                        <div class="form-group">
                            {{userForm.render('city-user', {'class': 'form-control select2'})}}
                        </div>   
                        <div class="form-group">
                            {{userForm.render('phone-user')}}
                        </div>
                    </div>
                </div>
                <hr />
                <div class="text-right wrap">
                    <small style="margin-right: 20px;">*<em>Todos los campos son necesarios.</em></small>
                    <a href="{{url('account/index')}}" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Cancelar">
                        <span class="glyphicon glyphicon-remove"></span>
                    </a>
                    <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Guardar">
                        <span class="glyphicon glyphicon-ok"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
{% endblock %}