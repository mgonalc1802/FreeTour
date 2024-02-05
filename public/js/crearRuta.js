//Espera a que la página cargue
$(function()
{
    //Indica que el textarea sea un richText
    $('.textArea').richText();

    //Llama a un método para asignar datePicker personalizados
    personalizaDatePicker();

    //Obtiene el botón
    var enviar = $("#enviar");
    var buscar = $("#buscarMapa");

    var items = $("#items");

    traerItems(items);

    buscar.click(function(ev)
    {
        ev.preventDefault();
        generaMapa();
    });

    enviar.click(function(ev)
    {
        ev.preventDefault();
        alert("hola");

        //Obtiene los datos del formulario
        var titulo = $("#titulo").val();
        var descripcion = $("#descripcion").val();   

        // foto = subirFoto();
        // console.log(foto);
        // traerItems();
    })
})

function personalizaDatePicker()
{
    //Indica que el input de fecha fin, esté deshabilitado
    $("#llegada").prop('disabled', true);

    //Creación de datapicker para las fechas
    //Crea el idioma español para datapicker ya que no está por defecto
    $.datepicker.regional['es'] = 
    {
        closeText: 'Cerrar',
        prevText: '< Ant',
        nextText: 'Sig >',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    
    //Indica el lenguaje de los datapicker que se usen
    $.datepicker.setDefaults($.datepicker.regional['es']);
    
    //Genera el datepicker de salida
    $("#salida").datepicker({minDate: 1, maxDate: "+1M"});

    //Dentro de la condición del tipo de viaje idaVuelta
    $("#salida").on("change", function() 
    {
        //Obtiene el valor de la fecha de salida
        var fechaSalida = $("#salida").datepicker("getDate");

        //Suma 7 días a la fecha de salida
        fechaLimite = fechaSalida.getDate() + 7;

        //Crea datepicker que tenga una semana después de salida y máximo dos meses. Lo habilita de nuevo
        $("#llegada").prop('disabled', false).datepicker({minDate: fechaLimite, maxDate: "+2M"});
    });
}

function generaMapa()
{
    //Obtiene el input que indica la dirección de la ruta
    var direccion = $("#indicaRuta").val();

    //Utilizar el servicio de geocodificación de OpenStreetMap Nominatim
    var nominatimURL = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(direccion);

    //Llamada ajax para obtener el mapa
    $.ajax(
    {
        url: nominatimURL,
        type: 'GET',
        dataType: 'json',
        success: function (data) 
        {
            if (data.length > 0) 
            {
                //Coordenadas del lugar
                var latitud = data[0].lat;
                var longitud = data[0].lon;

                //Hacer algo con las coordenadas (puedes mostrarlas en la consola o en un elemento HTML)
                $("#coordenadaInicio").val(latitud + ", " + longitud);

                //Configura tu mapa Leaflet
                var map = L.map('map').setView([latitud, longitud], 13);

                //Agrega una capa de mapa (por ejemplo, OpenStreetMap)
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                L.marker([latitud, longitud]).addTo(map)
                    .openPopup();
            } 
            else 
            {
                alert("No se encontraron coordenadas para la dirección proporcionada.");
            }
        },
        error: function () 
        {
            alert("Error al obtener las coordenadas. Por favor, inténtalo de nuevo.");
        }
    });
}

function subirFoto()
{
    $(".announce").html('<div class="alert alert-info">Processing...</div>');
        
    var formData = new FormData();
    var files = $('#image')[0].files[0];
    
    formData.append('file', files);
    
    $.ajax(
    {
        url: 'API/subirArchivos',
        type: 'post',
        data: formData,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function(response) 
        {
            console.log(response);
        }
    });

    return files.name;
}


function traerItems(elemento)
{
    $.ajax(
    {
        url: "json/items.json", //Indica la URL
        method: "get", //Indica el método
        dataType: "JSON", //Indica como viene
        timeout: 10,
        cache: false,
        success:function(data) //Respuesta si obtiene los datos necesarios
        {
            $.each(data, function(index, option) 
            {
                //Rellena el elemento con los items
                elemento.append('<option value="' + option.item + '">' + option.titulo + '</option>'); 
            });
        },
        error:function(err) //Respuesta si da error
        {
            //Muestra los datos por consola
            console.log(err); 
        }
    })
}