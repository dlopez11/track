{% extends "templates/default.volt" %}
{% block header %}
    {# Select 2 #}
    {{ javascript_include('library/select2/js/select2.min.js') }}
    {{ stylesheet_link('library/select2/css/select2.min.css') }}
    {# Switch #}
    {{ javascript_include('library/bootstrap-switch/js/bootstrap-toggle.min.js') }}
    {{ stylesheet_link('library/bootstrap-switch/css/bootstrap-toggle.min.css') }}
    {# Seletc State/City #}
    {{ javascript_include('library/jquery-select-change/select_jquery_account.js') }}
    {{ javascript_include('library/jquery-select-change/select_jquery_user.js') }}
    <script type="text/javascript">
        $(function () {
            $('#toggle-one').bootstrapToggle({
               on: 'On',
               off: 'Off',
               onstyle: 'success',
               offstyle: 'danger',
               size: 'small'
           });
           $(".select2").select2(); 
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
            <form action="{{url('account/add')}}" method="post" onload="javascript:city(arr);">
                <div class="row">
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
                            {{accountForm.render('state', {'class': 'form-control select2', 'id':'state'})}}
                        </div>
                        <div class="form-group">
                            {{accountForm.render('city', {'class': 'form-control select2', 'id':'city'})}}
                        </div>    
                        <div class="form-group">
                            {{accountForm.render('phone')}}
                        </div>
                        <div class="form-group">
                            {{accountForm.render('totalUsers')}}
                        </div>
                        <div class="form-group ">
                            Estado:<br />
                            {{accountForm.render('status', {'id': 'toggle-one', 'checked':'checked'})}}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 wrap">
                        <h3>Usuario administrador</h3>
                        <hr />
                        <div class="form-group">
                            {{userForm.render('name_user')}}
                        </div>
                        <div class="form-group">
                            {{userForm.render('lastName')}}
                        </div>
                        <div class="form-group">
                            {{userForm.render('email')}}
                        </div>
                        
                        <div class="form-group">
                            {{userForm.render('address_user')}}
                        </div>
                        <div class="form-group">
                            {{userForm.render('state_user', {'class': 'form-control select2', 'id':'state_user'})}}
                        </div>   
                        <div class="form-group">
                            {{userForm.render('city_user', {'class': 'form-control select2', 'id':'city_user'})}}
                        </div>   
                        <div class="form-group">
                            {{userForm.render('phone_user')}}
                        </div>
                        
                        <div class="form-group">
                            {{userForm.render('userName')}}
                        </div>
                        <div class="form-group">
                            {{userForm.render('pass')}}
                        </div>
                        <div class="form-group">
                            {{userForm.render('pass2')}}
                        </div>
                    </div>
                </div>
                <hr />
                <div style="margin-bottom: 17px;" class="text-right wrap">
                    <small style="margin-right: 20px;">*<em>Todos los campos son necesarios.</em></small>
                    <a href="{{url('account/index')}}" class="btn btn-default btn-sm">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-success btn-sm">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
{% endblock %}