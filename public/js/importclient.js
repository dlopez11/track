$(function(){
    $('#subida').submit(function(){

        var comprobar = $('#csv').val().length;

        if(comprobar > 0){
            var formulario = $('#subida');
            var archivos = new FormData();	
            var url = '../php/importarCSV.php';

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

                    $('#respuesta').html('<center><img src="../recursos/cargando.gif" width="50" heigh="50"></center>');	

                },
                success: function(data){

                    if(data == 'OK'){
                        $('#respuesta').html('<label style="padding-top:10px; color:green;">Importacion de CSV exitosa</label>');	
                        return false;	
                    }
                    else
                    {
                        $('#respuesta').html('<label style="padding-top:10px; color:red;">Error en la importacion del CSV</label>');
                        return false;
                    }
                }
            });
            
            return false;
        }        
        else         
        {            
            alert('Selecciona un archivo CSV para importar');
            return false;
        }
    });
});
