{% extends "templates/default.volt" %}
{% block header %}
{% endblock %}
{% block content %}
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h2>Edición de la contraseña del Usuario: <strong>{{user.userName}}</strong> </h2>
            <hr />
        </div>
    </div>
    
    <div class="clearfix"></div>
    <div class="space"></div>
    
    {{flashSession.output()}}
    
    <div class="row">
        <div class="col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-2 col-md-8 col-lg-offset-2 col-lg-8">
            <form action="{{url('user/passedit')}}/{{(user.idUser)}}" method="post" class="form-horizontal">
                               
                <div class="form-group">                                        
                    <input type="password" class="form-control" min="8" autofocus name="pass1" placeholder="*Digite la contraseña:">                    
                </div>

                <div class="form-group">                                        
                    <input type="password" class="form-control" min="8"  name="pass2" placeholder="*Repita la contraseña:">
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