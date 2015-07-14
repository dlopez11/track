$(function(){
    $('#up').click(function(){

        var comprobar = $('#csv').val().length;

        if(comprobar > 0){
            var formulario = $('#subida');
            var archivos = new FormData();
            var url = csv;

            for (var i = 0; i < (formulario.find('input[type=file]').length); i++) { 
                archivos.append((formulario.find('input[type="file"]:eq('+i+')').attr("name")),((formulario.find('input[type="file"]:eq('+i+')')[0]).files[0]));
            }
            $.ajax({                
                url: url,
                type: 'POST',
                contentType: false, 
                data: archivos,
                processData:false,
                beforeSend : function (){
                    $('#respuesta').html('<label style="padding-top:10px; color:blue;">Cargando</label>');
                },
                success: function(data){
                    if(data.length > 0){
                        $('#respuesta').html('<label style="padding-top:10px; color:green;">Importacion de CSV exitosa <a href='+client+' style="color:green;">- ver clientes</a></label>');	
                        $('#subida')[0].reset();
                        return false;	
                    }
                    else
                    {
                        $('#respuesta').html('<label style="padding-top:10px; color:red;">Error en la importacion del CSV</label>');
                        return false;
                    }                
            
                    return false;
                },
                error: function (data) {
                    $('#respuesta').html('<label style="padding-top:10px; color:red;">Por favor seleccione un archivo de tipo CSV</label>');
                    return false;
                }
    });    
        }
        else         
        {            
            alert('Selecciona un archivo CSV para importar.');
            return false;
        }
    });
    
    return false;
});
