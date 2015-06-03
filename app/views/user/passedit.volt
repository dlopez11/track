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
            <h2>Edición de la contraseña</h2>
            <hr />
        </div>
    </div>
    
    <div class="space"></div>
    
    <div class="row">
        <div class="col-md-12">
            {{flashSession.output()}}
                
            <form action="{{url('user/passedit')}}/{{(user.idUser)}}" method="post" class="form-horizontal">
                <div class="row">            
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 wrap form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">*Contraseña</label>
                            <div class="col-sm-10">
                              <input type="password" class="form-control" min="8" autofocus name="pass1">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">*Repita la contraseña</label>
                            <div class="col-sm-10">
                              <input type="password" class="form-control" min="8" autofocus name="pass2">
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 wrap form-horizontal">
                    <div class="text-right wrap">
                        <small style="margin-right: 20px;">*<em>Todos los campos son necesarios.</em></small>
                        <a href="{{url('user/index')}}/{{(user.idAccount)}}" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Cancelar">
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