$(function()
{
    //Obtiene el div dónde se encuentran las coordenadas
    var divCoor = $("#Item_coordenadas").parent();

    //Crea un botón que añadiremos a dicho div
    var buscarMapa = "<input type = 'button' id = 'buscarMapa' value = 'Ver Mapa' onclick = 'generaMapa()'></input>";

    //Crea el mapa que será modal
    var mapa = "<div id = 'mapa'></div>";

    //Introduce buscarMapa en divCoor
    divCoor.append(buscarMapa);

    //Introduce el mapa en divCoor
    divCoor.append(mapa);

    //Indica que el mapa es un modal
    $("#mapa").dialog(
    {
        autoOpen: false, //No se autoabre
        modal: true, //Es un modal
        show: //Efectos al abrir
            {
                effect: "blind",
                duration: 1000
            },
        hide: //Efectos al cerrar
            { 
                effect: "explode",
                duration: 1000
            },
        width: 1000, //Altura
        height: 600 //Ancho
    });
});

function generaMapa()
{
    //Obtiene el input dónde se escribirán las coordenadas
    var coordenadas = $("#Item_coordenadas");

    //Abre un modal con el mapa
    $("#mapa").dialog( "open" );

    //Inicializa el mapa de Leaflet, generando L que es una variable global.
    var map = L.map('mapa').setView([37.77, -3.79], 15);

    //Añade un marcador al mapa por defecto, definido en Jaén Capital
    var marker = L.marker([37.77, -3.79]).addTo(map);

    L.tileLayer('https://tile.jawg.io/jawg-streets/{z}/{x}/{y}{r}.png?access-token={accessToken}', 
    {
        minZoom: 0,
        maxZoom: 22,
        accessToken: 'rSpYFAsrP0xh9UQNavI4LCwxGfaAzB4OVL9PGe4rABoU6l1awbhA9ORdSGE8m515'
    }).addTo(map);
    
    map.on('click', function (e) 
    {
        //Define las coordenadas del marker donde se ha hecho clic
        marker.setLatLng(e.latlng);

        //Pone las coordenadas del marker en el input de coordenadas
        coordenadas.val(e.latlng.lat + ", " + e.latlng.lng);
    });

    //Comprueba el click del boton cerrar del modal
    $(".ui-dialog-titlebar-close").click( function ()
    {
        //Cierra el mapa
        map.off();

        //Borra el mapa
        map.remove();
    });
}