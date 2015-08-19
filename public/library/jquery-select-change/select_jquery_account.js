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

var CAQUETA = [
    {display: "FLORENCIA", value: "FLORENCIA" }
];

var CASANARE = [
    {display: "YOPAL", value: "YOPAL" }
];

var CAUCA = [
    {display: "POPAYAN", value: "POPAYAN" },
    {display: "SANTANDER DE QUILICHAO", value: "SANTANDER DE QUILICHAO" }
];

var CESAR = [
    {display: "VALLEDUPAR", value: "VALLEDUPAR" },
    {display: "AGUACHICA", value: "AGUACHICA" }
];

var CHOCO = [
    {display: "QUIBDO", value: "QUIBDO" }
];

var CORDOBA = [
    {display: "MONTERIA", value: "MONTERIA" },
    {display: "CERETE", value: "CERETE" },
    {display: "MONTELIBANO", value: "MONTELIBANO" },
    {display: "PLANETA RICA", value: "PLANETA RICA" },
    {display: "SAN BERNARDO DEL VIENTO", value: "SAN BERNARDO DEL VIENTO" },
    {display: "TIERRALTA", value: "TIERRALTA" }
];

var CUNDINAMARCA = [
    {display: "BOGOTA", value: "BOGOTA" },
    {display: "FACATATIVA", value: "FACATATIVA" },
    {display: "FUSAGASUGA", value: "FUSAGASUGA" },
    {display: "GIRARDOT", value: "GIRARDOT" },
    {display: "CHIA", value: "CHIA" },
    {display: "TABIO", value: "TABIO" },
    {display: "GUADUAS", value: "GUADUAS" },
    {display: "MADRID", value: "MADRID" },
    {display: "CAJICÁ", value: "CAJICÁ" },
    {display: "COTA", value: "COTA" },
    {display: "VILLETA", value: "VILLETA" },
    {display: "TENJO", value: "TENJO" },
    {display: "ZIPAQUIRÁ", value: "ZIPAQUIRÁ" },
    {display: "CHAGUANI", value: "CHAGUANI" },
    {display: "SOACHA", value: "SOACHA" }
];

var GUAINIA = [
    {display: "INIRIDA", value: "INIRIDA" }
];

var GUAVIARE = [
    {display: "SAN JOSE DEL GUAVIARE", value: "SAN JOSE DEL GUAVIARE" }
];

var GUAJIRA = [
    {display: "RIOACHA", value: "RIOACHA" },
    {display: "MAICAO", value: "MAICAO" }
];

var HUILA = [
    {display: "NEIVA", value: "NEIVA" },
    {display: "PITALITO", value: "PITALITO" }
];

var MAGDALENA = [
    {display: "SANTA MARTA", value: "SANTA MARTA" },
    {display: "CIENAGA", value: "CIENAGA" },
    {display: "PLATO", value: "PLATO" }
];

var META = [
    {display: "VILLAVICENCIO", value: "VILLAVICENCIO" },
    {display: "ACACIAS", value: "ACACIAS" },
    {display: "GRANADA", value: "GRANADA" }
];

var NARIÑO = [
    {display: "PASTO", value: "PASTO" },
    {display: "IPIALES", value: "IPIALES" },
    {display: "TUMACO", value: "TUMACO" }
];

var NTE_SANTANDER = [
    {display: "CUCUTA", value: "CUCUTA" },
    {display: "OCAÑA", value: "OCAÑA" },
    {display: "VILLA DEL ROSARIO", value: "VILLA DEL ROSARIO" }
];

var PUTUMAYO = [
    {display: "MOCOA", value: "MOCOA" },
    {display: "PUERTO ASIS", value: "PUERTO ASIS" }
];

var QUINDIO = [
    {display: "ARMENIA", value: "ARMENIA" },
    {display: "CALARCA", value: "CALARCA" }
];

var RISARALDA = [
    {display: "PEREIRA", value: "PEREIRA" },
    {display: "DOSQUEBRADAS", value: "DOSQUEBRADAS" },
    {display: "SANTA ROSA DE CABAL", value: "SANTA ROSA DE CABAL" }
];

var SAN_ANDRES = [
    {display: "SAN ANDRES", value: "SAN ANDRES" }
];

var SANTANDER = [
    {display: "BUCARAMANGA", value: "BUCARAMANGA" },
    {display: "BARRANCABERMEJA", value: "BARRANCABERMEJA" },
    {display: "FLORIDABLANCA", value: "FLORIDABLANCA" },
    {display: "GIRON", value: "GIRON" },
    {display: "PIEDECUESTA", value: "PIEDECUESTA" }
];

var SUCRE = [
    {display: "SINCELEJO", value: "SINCELEJO" },
    {display: "COROZAL", value: "SINCELEJO" }
];

var TOLIMA = [
    {display: "IBAGUE", value: "IBAGUE" },
    {display: "ESPINAL", value: "ESPINAL" }
];

var VALLE = [
    {display: "CALI", value: "CALI" },
    {display: "BUENAVENTURA", value: "BUENAVENTURA" },
    {display: "BUGA", value: "BUGA" },
    {display: "CARTAGO", value: "CARTAGO" },
    {display: "JAMUNDI", value: "JAMUNDI" },
    {display: "PALMIRA", value: "PALMIRA" },
    {display: "TULUA", value: "TULUA" },
    {display: "YUMBO", value: "YUMBO" }
];

var VAUPES = [
    {display: "MITU", value: "MITU" }
];

var VICHADA = [
    {display: "PUERTO CARREÑO", value: "PUERTO CARREÑO" }
];

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

case "CAQUETA":
    city(CAQUETA);
break;

case "CASANARE":
    city(CASANARE);
break;

case "CAUCA":
    city(CAUCA);
break;

case "CESAR":
    city(CESAR);
break;

case "CHOCO":
    city(CHOCO);
break;

case "CORDOBA":
    city(CORDOBA);
break;

case "CUNDINAMARCA":
    city(CUNDINAMARCA);
break;

case "GUAINIA":
    city(GUAINIA);
break;

case "GUAVIARE":
    city(GUAVIARE);
break;

case "GUAJIRA":
    city(GUAJIRA);
break;

case "HUILA":
    city(HUILA);
break;

case "MAGDALENA":
    city(MAGDALENA);
break;

case "META":
    city(META);
break;

case "NARIÑO":
    city(NARIÑO);
break;

case "NTE_SANTANDER":
    city(NTE_SANTANDER);
break;

case "PUTUMAYO":
    city(PUTUMAYO);
break;

case "QUINDIO":
    city(QUINDIO);
break;

case "RISARALDA":
    city(RISARALDA);
break;

case "SAN_ANDRES":
    city(SAN_ANDRES);
break;

case "SANTANDER":
    city(SANTANDER);
break;

case "SUCRE":
    city(SUCRE);
break;

case "TOLIMA":
    city(TOLIMA);
break;

case "VALLE":
    city(VALLE);
break;

case "VAUPES":
    city(VAUPES);
break;

case "VICHADA":
    city(VICHADA);
break;

default:
    $("#city").empty();
    $("#city").append("<option>*Seleccionar ciudad</option>");
break;
}
});

//Function To List out Cities in Second Select tags
function city(arr){
    $("#city").empty();//To reset cities
    $("#city").append("<option>*Seleccionar Ciudad</option>");
    $(arr).each(function(i){//to list cities
        $("#city").append("<option value=\""+arr[i].value+"\">"+arr[i].display+"</option>")
    });
}

});