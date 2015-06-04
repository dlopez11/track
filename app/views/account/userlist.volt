{% extends "templates/default.volt" %}
{% block header %}
    <script type="text/javascript">
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
          });
          
          $('#myModal').on('shown.bs.modal', function () {
            $('#myInput').focus();
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
        <a href="{{url('account/index')}}" class="btn btn-default">Regresar a lista de Cuentas</a>
        <a href="{{url('user/add')}}/{{(userData.idAccount)}}" class="btn btn-success">Crear nuevo Usuario</a>
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
                        <th>Tipo de Usuario</th>
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
                        <td>{{item.role.name}}</td>
                        <td>{{item.email}}</td>
                        <td>{{item.address}}</td>
                        <td>{{item.phone}}</td>
                        <td>
                            {{item.city}}<br />
                            {{item.state}}
                        </td>
                        <td class="text-right">
                            <a href="{{url('user/passedit')}}/{{item.idUser}}" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="Editar contraseña"><span class="glyphicon glyphicon-lock"></span></a>
                            <a href="{{url('user/edit')}}/{{item.idUser}}" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="Editar usuario"><span class="glyphicon glyphicon-pencil"></span></a>                            
                            <button id="delete" data-id="{{url('user/delete')}}/{{item.idUser}}" type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#myModal">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
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
    
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">              
              <h2 class="modal-title" id="myModalLabel">Eliminar usuario</h2>
            </div>
            <div class="modal-body">
                ¿Esta seguro de eliminar este usuario?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" >Cancelar</button>
              <a href="#" id="btn-ok" class="btn btn-success btn-sm">Confirmar</a>
            </div>
          </div>
        </div>
    </div>
    
    <script>
        $(document).on("click", "#delete", function () {
            var myURL = $(this).data('id');
            $("#btn-ok").attr('href', myURL );
        });       
    </script>
    
{% endblock %}