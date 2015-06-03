{% extends "templates/default.volt" %}
{% block header %}
    <script type="text/javascript">
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
          });
    </script>
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
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 wrap form-horizontal">
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
                            {{accountForm.render('city', {'class': 'form-control'})}}
                        </div>    
                        <div class="form-group">
                            {{accountForm.render('phone')}}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 wrap">
                        <h3>Usuario administrador</h3>
                        <hr />
                        {{userForm.render('userName')}}
                        <br /><br />
                        {{userForm.render('pass')}}
                        <br /><br />
                        {{userForm.render('pass2')}}
                        <br /><br />
                        {{userForm.render('email')}}
                        <br /><br />
                        {{userForm.render('name-user')}}
                        <br /><br />
                        {{userForm.render('lastName')}}
                        <br /><br />
                        {{userForm.render('address-user')}}
                        <br /><br />
                        {{userForm.render('phone-user')}}
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