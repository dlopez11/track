{% extends "templates/default.volt" %}
{% block header %}
    <script type="text/javascript">
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
          });
    </script>
{% endblock %}
{% block content %}
    <h2>Editar Cuenta: <em><strong>{{account_value.name}}</strong></em></h2>
    {{flashSession.output()}}
    <form action="{{url('account/edit')}}/{{account_value.idAccount}}" method="post">
        <div class="row form-horizontal">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 wrap">
                <h3><span class="glyphicon glyphicon-list"></span> Datos de la cuenta</h3>
                <hr />
                <input type="text" name="name" value ="{{account_value.name}}" placeholder="*Nombre de la cuenta" required="required" autofocus="autofocus" style="width:100%;">
                <br /><br />
                <input type="text" name="nit" value ="{{account_value.nit}}" placeholder="*NIT" required="required" style="width:100%;">
                <br /><br />
                <input type="text" name="address" value ="{{account_value.address}}" placeholder="*Dirección" required="required" style="width:100%;">
                <br /><br />
                <input type="text" name="city" value ="{{account_value.city}}" placeholder="*Ciudad" required="required" style="width:100%;">
                <br /><br />
                <input type="text" id="address" name="address" value ="{{account_value.phone}}" placeholder="*Teléfono" required="required" style="width:100%;">
            </div>
        </div>
        <hr />
        <div class="text-right wrap">
            <small style="margin-right: 20px;">*<em>Todos los campos son necesarios.</em></small>
            <a href="{{url('account/index')}}" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Cancelar">
                <span class="glyphicon glyphicon-remove"></span>
            </a>
            <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Guardar">
                <span class="glyphicon glyphicon-ok"></span>
            </button>
        </div>
    </form>
    <br />
{% endblock %}