{% extends "templates/default.volt" %}
{% block header %}
    <script type="text/javascript">
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
          });
    </script>
{% endblock %}
{% block content %}
    <br />
    <br />
    <br />
    <h1><span class="glyphicon glyphicon-list"></span> Lista de cuentas</h1>
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
                        <th>Ciudad</th>
                        <th>Teléfono</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {% for item in page.items %}
                    <tr {% if item.status == 0 %} class="account-disabled" {% endif %}>
                        <td>
                            <strong>
                                {{(item.idAccount)}} - {{item.name}}
                            </strong>
                        </td>
                        <td>{{item.nit}}</td>
                        <td>{{item.address}}</td>
                        <td>{{item.city}}</td>
                        <td>{{item.phone}}</td>
                        <td style="width: 12%;">
                            <a href="#" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Usuarios"><span class="glyphicon glyphicon-user"></span></a>
                            <a href="#" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Editar"><span class="glyphicon glyphicon-pencil"></span></a>
                            <a href="#" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar"><span class="glyphicon glyphicon-remove"></span></a>
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