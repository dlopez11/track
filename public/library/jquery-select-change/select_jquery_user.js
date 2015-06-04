$(document).ready(function(){

var AMAZONAS = [
    {display: "LETICIA", value: "LETICIA" }
];
    
var ANTIOQUIA = [
    {display: "MEDELLIN", value: "MEDELLIN" },
    {display: "BELLO", value: "BELLO" },
    {display: "CAUCASIA", value: "CAUCASIA" },
    {display: "ENVIGADO", value: "ENVIGADO" },
    {display: "ITAGUI", value: "ITAGUI" },
    {display: "RIONEGRO", value: "RIONEGRO" }
];
    
var ARAUCA = [
    {display: "ARAUCA", value: "ARAUCA" }
];

var ATLANTICO = [
    {display: "BARRANQUILLA", value: "BARRANQUILLA" },
    {display: "SOLEDAD", value: "SOLEDAD" }
];

var BOLIVAR = [
    {display: "CARTAGENA", value: "CARTAGENA" }
];

var BOYACA = [
    {display: "TUNJA", value: "TUNJA" },
    {display: "SOGAMOSO", value: "SOGAMOSO" },
    {display: "DUITAMA", value: "DUITAMA" }
];

var CALDAS = [
    {display: "MANIZALES", value: "MANIZALES" },
    {display: "LA DORADA", value: "LA DORADA" },
    {display: "CHINCHINA", value: "CHINCHINA" }
];

$("#state_user").change(function(){

var select = $("#state_user option:selected").val();

switch(select){
case "AMAZONAS":
    city(AMAZONAS);
break;

case "ANTIOQUIA":
    city(ANTIOQUIA);
break;

case "ARAUCA":
    city(ARAUCA);
break;

case "ATLANTICO":
    city(ATLANTICO);
break;

case "BOLIVAR":
    city(BOLIVAR);
break;

case "BOYACA":
    city(BOYACA);
break;

case "CALDAS":
    city(CALDAS);
break;

default:
    $("#city_user").empty();
    $("#city_user").append("<option>--Seleccionar ciudad--</option>");
break;
}
});

//Function To List out Cities in Second Select tags
function city(arr){
    $("#city_user").empty();//To reset cities
    $("#city_user").append("<option>--Seleccionar ciudad--</option>");
    $(arr).each(function(i){//to list cities
        $("#city_user").append("<option value=\""+arr[i].value+"\">"+arr[i].display+"</option>")
    });
}

});