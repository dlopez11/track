{% extends "templates/default.volt" %}
{% block header %}
{% endblock %}
{% block content %}
    <div class="row">
        <div class="col-md-12">
            <h2>Edición de la información del Usuario</h2>
            <hr />
        </div>
    </div>
    
    <div class="space"></div>
    
    <div class="row">
        <div class="col-md-12">
            {{flashSession.output()}}

            <form action="{{url('user/edit')}}//{{(user.idUser)}}" method="post">
                <div class="row">            
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 wrap form-horizontal">
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
                            {{UserForm.render('city_user', {'class': 'form-control select2'})}}
                        </div>
                        <div class="form-group">
                            {{UserForm.render('state_user', {'class': 'form-control select2'})}}
                        </div>                        
                        <div class="form-group">
                            {{UserForm.render('phone_user')}}
                        </div>
                    </div>
                </div>
                <hr />
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 wrap form-horizontal">
                    <div class="text-right wrap">
                        <small style="margin-right: 20px;">*<em>Todos los campos son necesarios.</em></small>
                        <a href="{{url('user/index')}}" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Cancelar">
                            <span class="glyphicon glyphicon-remove"></span>
                        </a>
                        <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Guardar">
                            <span class="glyphicon glyphicon-ok"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
{% endblock %}