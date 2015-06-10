{% extends "templates/default.volt" %}
{% block header %}
    {# Select 2 #}
    {{ javascript_include('library/select2/js/select2.min.js') }}
    {{ stylesheet_link('library/select2/css/select2.min.css') }}
    {# Seletc State/City #}
    {{ javascript_include('library/jquery-select-change/select_jquery_account.js') }}
    {{ javascript_include('library/jquery-select-change/select_jquery_user.js') }}
    <script type="text/javascript">
        $(function () {
            
           $(".select2").select2();
           
          });
    </script>
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
            <form action="{{url('account/edituser')}}//{{(user.idUser)}}" method="post" onload="javascript:city(arr);">               
                
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
                    {{UserForm.render('state_user', {'class': 'form-control select2' , 'id':'state_user'})}}
                </div>
                <div class="form-group">
                    <select id="city_user" name="city_user" class="form-control select2">
                        <option value="{{user.city}}">{{user.city}}</option>
                    </select>
                </div>
                <div class="form-group">
                    {{UserForm.render('phone_user')}}
                </div>
                                                   
                <div class="form-group text-right">
                    <small style="margin-right: 20px;">*<em>Todos los campos son necesarios.</em></small>
                    <a href="{{url('account/userlist')}}/{{user.idAccount}}" class="btn btn-default btn-sm">Cancelar</a>
                    <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                </div>
            </form>
        </div>
    </div>
{% endblock %}