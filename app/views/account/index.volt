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
            <h2>Lista de Cuentas</h2>
            <hr />
        </div>
    </div>    
    
    <div class="space"></div>
    
    {{flashSession.output()}}
    <div class="text-right">
        <a href="{{url('account/add')}}" class="btn btn-success">
            Crear nueva cuenta
        </a>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 wrap">
            {{ partial('partials/pagination_static_partial', ['pagination_url': 'account/index']) }}
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 wrap">
            <table class="table table-responsive table-bordered">                
                <thead class="th">
                    <tr>
                        <th>Nombre</th>
                        <th>NIT</th>
                        <th>Dirección</th>
                        <th>Ubicación</th>
                        <th>Teléfono</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {% for item in page.items %}
                    <tr {% if item.status == 0 %} class="account-disabled" {% endif %}>
                        <td>
                            <strong>{{item.name}}</strong><br />
                            <span class="xs-text">Creado el {{date('d/M/Y', item.created)}}</span> <br />
                            <span class="xs-text">Actualizado el {{date('d/M/Y', item.updated)}}</span>
                        </td>
                        <td>{{item.nit}}</td>
                        <td>{{item.address}}</td>
                        <td>
                            {{item.city}}
                            <br />
                            {{item.state}}
                        </td>
                        <td>{{item.phone}}</td>
                        <td style="width: 12%;">
                            <a href="{{url('user/index')}}" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Usuarios"><span class="glyphicon glyphicon-user"></span></a>
                            <a href="{{url('account/edit')}}/{{item.idAccount}}" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Editar"><span class="glyphicon glyphicon-pencil"></span></a>
                        </td>
                    </tr>
                    {% endfor %}
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 wrap">
            {{ partial('partials/pagination_static_partial', ['pagination_url': 'account/index']) }}
        </div>
    </div>
{% endblock %}