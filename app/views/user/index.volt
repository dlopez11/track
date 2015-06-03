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
            <h2>Lista de Usuarios</h2>
            <hr />
        </div>
    </div>
    
    <div class="space"></div>
    
    {{flashSession.output()}}
    <div class="text-right">
        <a href="{{url('user/add')}}" class="btn btn-success">
            Crear nuevo Usuario
        </a>
    </div>
    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 wrap">
            {{ partial('partials/pagination_static_partial', ['pagination_url': 'user/index']) }}
        </div>
    </div>
    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 wrap">
            <table class="table table-responsive table-bordered">                
                <thead class="th">
                    <tr>
                        <th>Nombre</th>
                        <th>Nombre de Usuario</th>
                        <th>Email</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Ciudad</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                {% for item in page.items %}
                    <tr>
                        <td>
                            <strong>
                                {{(item.idUser)}} - {{item.name}} {{item.lastName}}
                            </strong>
                            <br />
                            <span class="xs-text">Creado el {{date('d/M/Y', item.created)}}</span><br />
                            <span class="xs-text">Actualizado el {{date('d/M/Y', item.updated)}}</span>                                                     
                        </td>
                        <td>{{item.userName}}</td>
                        <td>{{item.email}}</td>
                        <td>{{item.address}}</td>
                        <td>{{item.phone}}</td>
                        <td>
                            {{item.city}}<br />
                            {{item.state}}
                        </td>
                        <td style="width: 12%;">
                            <a href="{{url('user/passedit')}}/{{item.idUser}}" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Editar contraseña"><span class="glyphicon glyphicon-lock"></span></a>
                            <a href="{{url('user/edit')}}/{{item.idUser}}" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Editar usuario"><span class="glyphicon glyphicon-pencil"></span></a>
                            <a href="{{url('user/delete')}}" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Borrar usuario"><span class="glyphicon glyphicon-trash"></span></a>
                        </td>
                    </tr>
                    {% endfor %}
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 wrap">
            {{ partial('partials/pagination_static_partial', ['pagination_url': 'user/index']) }}
        </div>
    </div>
    
{% endblock %}