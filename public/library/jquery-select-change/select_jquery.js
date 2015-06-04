$(document).ready(function(){

var AMAZONAS = [
    {display: "LETICIA", value: "LETICIA" }];
    
var ANTIOQUIA = [
    {display: "APARTADO", value: "APARTADO" },
    {display: "BELLO", value: "BELLO" },
    {display: "MEDELLIN", value: "MEDELLIN" },
    {display: "TURBO", value: "TURBO" }
];
    
var ARAUCA = [
    {display: "ARAUCA", value: "ARAUCA" }];

$("#state").change(function(){

var select = $("#state option:selected").val();

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

default:
    $("#city").empty();
break;
}
});

//Function To List out Cities in Second Select tags
function city(arr){
    $("#city").empty();//To reset cities
    $(arr).each(function(i){//to list cities
            $("#city").append("<option value=\""+arr[i].value+"\">"+arr[i].display+"</option>")
    });
}
});