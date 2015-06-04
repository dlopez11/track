{% extends "templates/default.volt" %}
{% block header %}
    {# Select 2 #}
    {{ javascript_include('library/select2/js/select2.min.js') }}
    {{ stylesheet_link('library/select2/css/select2.min.css') }}
    {# Seletc State/City #}
    {{ javascript_include('library/jquery-select-change/select_jquery_account.js') }}
    {{ javascript_include('library/jquery-select-change/select_jquery_user.js') }}
{% endblock %}
{% block content %}
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h2>Agregar un nuevo cliente</h2>
            <hr />
        </div>        
    </div>
                    
    <div class="clearfix"></div>
    <div class="space"></div>     
    
    {{flashSession.output()}}         
    
    <div class="row">
        <div class="col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-2 col-md-8 col-lg-offset-2 col-lg-8">
            <form action="{{url('client/add')}}" method="post" class="form-horizontal" onload="javascript:city(arr);">
                <div class="form-group">
                    {{form.render('name')}}
                </div>
                <div class="form-group">
                    {{form.render('description')}}
                </div>
                <div class="form-group">
                    {{form.render('nit')}}
                </div>
                <div class="form-group">
                    {{form.render('address')}}
                </div>
                <div class="form-group">
                    {{form.render('state', {'class': 'form-control select2', 'id':'state'})}}
                </div>
                <div class="form-group">
                    {{form.render('city', {'class': 'form-control select2', 'id':'city'})}}
                </div>
                <div class="form-group">
                    {{form.render('phone')}}
                </div>
                <div class="form-group text-right">
                    <a href="{{url('client')}}" class="btn btn-sm btn-default">Cancelar</a>
                    <button class="btn btn-sm btn-success">Guardar</button>
                </div>
            </form>    
        </div>        
    </div>
{% endblock %}