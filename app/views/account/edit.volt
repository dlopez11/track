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
                <div class="form-group">
                    {{accountForm.render('totalUsers')}}
                </div>
                <div class="form-group ">
                    {{accountForm.render('status', {'id': 'toggle-one'})}}
                </div>
                <div class="text-right wrap">
                    <small style="margin-right: 20px;">*<em>Todos los campos son necesarios.</em></small>
                    <a href="{{url('account/index')}}" class="btn btn-default btn-sm">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-success btn-sm">
                        Guardar
                    </button>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 wrap"></div>
        </div>
    </form>
    <br />
{% endblock %}