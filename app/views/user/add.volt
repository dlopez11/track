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
            <h2>Nueva Usuario</h2>
            <hr />
        </div>
    </div>
    
    <div class="space"></div>
    
    <div class="row">
        <div class="col-md-12">
            {{flashSession.output()}}

            <form action="{{url('user/add')}}/{{(account.idAccount)}}" method="post">
                <div class="row">            
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 wrap form-horizontal">                
                        <h3>Datos del Usuario</h3>
                        <hr />

                        <div class="form-group">
                            {{UserForm.render('userName')}}
                        </div> 
                        <div class="form-group">
                            {{UserForm.render('pass')}}
                        </div> 
                        <div class="form-group">
                            {{UserForm.render('pass2')}}
                        </div>                         
                        <div class="form-group">
                            {{UserForm.render('name-user')}}
                        </div> 
                        <div class="form-group">
                            {{UserForm.render('lastName')}}
                        </div>
                        <div class="form-group">
                            {{UserForm.render('email')}}
                        </div>
                        <div class="form-group">
                            {{UserForm.render('address-user')}}
                        </div> 
                        <div class="form-group">
                            {{UserForm.render('phone-user')}}
                        </div>
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
        </div>
    </div>
    
{% endblock %}