$(function()
{
    //Llama a una función para rellenar un select
    traeGuias();

    //Obtiene el div
    var calendarioAdmin = $("#calendarioAdmin");

    //Lo introduce en el contenedor de easyadmin
    $(".content").append(calendarioAdmin);

    //Obtiene el header de la plantilla de easyAdmin
    $(".content-header-title h1").append('Calendario');

    //Obtiene el calendario del html
    var calendarEl = document.getElementById('calendar');

    //Crea un FullCalendar
    var calendar = new FullCalendar.Calendar(calendarEl, 
    {
        timeZone: 'GMT+1',
        themeSystem: 'bootstrap5',
        headerToolbar: 
        {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        weekNumbers: true,
        dayMaxEvents: true,
        events: "/API/getTours",
        eventClick: function(info) 
        {
            //Muestra el guia en el span
            document.getElementById('modalGuia').innerText = info.event.extendedProps.guia;

            //Muestra el nombre de la ruta en el span
            document.getElementById('modalRuta').innerText = info.event.title;

            //Inserta el id del tour en el span oculto
            document.getElementById('idTour').innerText = info.event.extendedProps.idTour;
        
            //Crear y abrir un modal
            var modal = new bootstrap.Modal(document.getElementById('myModal'));
            modal.show();

            //Cambia el borde del evento
            info.el.style.borderColor = 'red';
        }
    });

    //Muestra el calencario
    calendar.render();

    //Obtiene el boton guardar para modificar el guia del tour
    var guardar = $("#guardar");

    //Genera el evento click del boton
    guardar.click(function(ev)
    {
        //Previene el submit que realiza por defecto
        ev.preventDefault();

        //Obtiene el valor del select
        modificarTour();
    })
});

function traeGuias()
{
    //Llamada AJAX que se encarga de traer Guia
    $.ajax(
    {
        url: "/API/guias",
        type: 'GET',
        dataType: 'json',
        contentType: 'application/json', 
        processData: false,
        success: function (response) 
        {
            //Obtiene el contenido html necesario
            var selectGuias = $("#guias");

            //Recorre el array que obtiene a través de la API
            for(let i = 0; i < response.length; i++)
            {
                //Crea un nuevo elemento del select
                var option = $('<option value = "' + response[i].nombre + ' ' + response[i].apellido + '">' + response[i].nombre + ' ' + response[i].apellido +'</option>');

                //Añade al select la opción
                selectGuias.append(option);
            }       
        }
    });
}

function modificarTour()
{
    var idTour = $("#idTour")[0].innerHTML;
    var guia = $("#guias").val();

    //Llamada AJAX que se encarga de traer Guia
    var json = 
    {
        "id": idTour,
        "guia": guia
    };
    
    //Llamada AJAX que se encarga de insertar Ruta
    $.ajax(
        {
            url: "/API/modificarTour",
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify(json), 
            contentType: 'application/json', 
            processData: false,
            success: function (response) 
            {
                window.location.href = "/admin";
            }
        });
}