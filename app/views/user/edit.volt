{% extends "templates/default.volt" %}
{% block header %}
{% endblock %}
{% block content %}
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h2>Editar el Usuario: <strong>{{user.userName}}</strong></h2>
            <hr />
        </div>
    </div>
    
    <div class="clearfix"></div>    
    <div class="space"></div>
    
    {{flashSession.output()}}
    
    <div class="row">
        <div class="col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-2 col-md-8 col-lg-offset-2 col-lg-8">
            <form action="{{url('user/edit')}}//{{(user.idUser)}}" method="post">               
                
                <div class="form-group">
                    {{UserForm.render('userName')}}
                </div>
                <div class="form-group">
                    {{UserForm.render('idRole', {'class': 'form-control select2'})}}
                </div>                                                
                <div class="form-group">
                    {{UserForm.render('name_user')}}
                </div> 
                <div class="form-group">
                    {{UserForm.render('lastName')}}
                </div>
                <div class="form-group">
                    {{UserForm.render('email')}}
                </div>
                <div class="form-group">
                    {{UserForm.render('address_user')}}
                </div>                
                <div class="form-group">
                    {{UserForm.render('state_user', {'class': 'form-control select2'})}}
                </div>
                <div class="form-group">
                    {{UserForm.render('city_user', {'class': 'form-control select2'})}}
                </div>
                <div class="form-group">
                    {{UserForm.render('phone_user')}}
                </div>
                                                   
                <div class="form-group text-right">
                    <small style="margin-right: 20px;">*<em>Todos los campos son necesarios.</em></small>
                    <a href="{{url('user/index')}}" class="btn btn-default btn-sm">Cancelar</a>
                    <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                </div>
            </form>
        </div>
    </div>
{% endblock %}