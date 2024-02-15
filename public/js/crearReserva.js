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

})

//Crea una variable
var actual = 0;

//Función que cambia los botones
function puntos(n)
{
    //Obtiene los puntos 
    var ptn = document.getElementsByClassName("punto");

    //Bucle que recorre los puntos
    for(i = 0; i<ptn.length; i++)
    {
        //Si es activo
        if(ptn[i].className.includes("activo"))
        {
            //Se remplaza al botón actual
            ptn[i].className = ptn[i].className.replace("activo", "");
            break;
        }
    }

    ptn[n].className += " activo";
}

//Muestra las imágenes
function mostrar(n)
{
    //Obtiene el html donde se encuentran las imágenes.
    var imagenes = document.getElementsByClassName("imagen");

    //Bucle que recorre el array
    for(i = 0; i< imagenes.length; i++)
    {
        //Si incluyen actual
        if(imagenes[i].className.includes("actual"))
        {
            //Se remplazan las imagenes
            imagenes[i].className = imagenes[i].className.replace("actual", "");
            break;
        }
    }
    
    //Cambia actual
    actual = n;

    //Indica la clase
    imagenes[n].className += " actual";

    //Llama a la función puntos
    puntos(n);
}

//Función que avanza imágenes
function siguiente()
{
    //Imcrementa actual
    actual++;
    
    //Si actual es mayor que tres
    if(actual > 3)
    {
        //Vuelve a 0, que indica la posición de la imagen
        actual = 0;
    }

    //Muestra la imagen
    mostrar(actual);
}

//Función que retrocede imágenes
function anterior()
{
    //Desincrementa actual
    actual--;

    //Si actual es menor que 0
    if(actual < 0)
    {
        //Vuelve a 3, que indica la posición de la imagen
        actual = 3;
    }

    //Muestra imagen
    mostrar(actual);
}

//Crea la variable velocidad 
var velocidad = 7000;

//Genera un intervalo con dicha velocidad
var play = setInterval("siguiente()", velocidad);

//Genera una función
function playpause()
{
    //Si play es nulo
    if(play == null)
    {
        //Se incrementa la velocidad
        play = setInterval("siguiente()", velocidad);
    } 
}
