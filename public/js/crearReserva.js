$(function()
{
    var coordenadas = $("#coordenada").html();

    // Dividir las coordenadas en latitud y longitud
    var coordenadasArray = coordenadas.split(', ');

    // Obtener latitud y longitud
    var latitud = parseFloat(coordenadasArray[0]);
    var longitud = parseFloat(coordenadasArray[1]);

    //Inicializa el mapa de Leaflet, generando L que es una variable global.
    var map = L.map('mapa').setView([latitud, longitud], 16);

    //Añade un marcador al mapa por defecto, definido en el punto de inicio
    L.marker([latitud, longitud]).addTo(map);

    L.tileLayer('https://tile.jawg.io/jawg-streets/{z}/{x}/{y}{r}.png?access-token={accessToken}', 
    {
        minZoom: 0,
        maxZoom: 22,
        accessToken: 'rSpYFAsrP0xh9UQNavI4LCwxGfaAzB4OVL9PGe4rABoU6l1awbhA9ORdSGE8m515'
    }).addTo(map);

});

