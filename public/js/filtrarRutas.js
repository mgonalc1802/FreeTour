$(function()
{
    //Obtiene el botón por el que se fitrará
    var filtrar = $("#filtrar");

    //Ejecuta el evento click de dicho botón
    filtrar.click(function(ev)
    {
        //Previene el submit que tiene por defecto
        ev.preventDefault();

        //Se encarga de vaciar las rutas mostradas
        $("#vid-grid li").remove();

        //Obtiene el valor del input del que obtendrá el valor
        var localidad = $("#filtro").val();
        
        //Obtener las rutas de una localidad concreta
        traeRutas(localidad);

        //Vacía el input del que obtendrá el valor
        $("#filtro").val("");
    })
});

function traeRutas(localidad)
{
    //Llamada AJAX que se encarga de buscar las rutas de una localidad
    $.ajax(
        {
            url: "/API/rutas/localidad/" + localidad,
            type: 'GET',
            dataType: 'json',
            contentType: 'application/json', 
            processData: false,
            success: function (response) 
            {
                //Obtiene el contenido html necesario, en este caso una lista desordenada
                var ul = $("#vid-grid");

                //Crea un nuevo elemento del li
                var li = $('<li class = "thumb-wrap">\
                                <a href="/reserva/id=' + response[0].id + '">\
                                    <img class = "thumb" src = "/images/ruta/' + response[0].urlFoto + '" alt = "' + response[0].urlFoto + '" width = "341" height="260">\
                                    <div class = "thumb-info">\
                                        <p class="thumb-title text-primary text-uppercase" style="letter-spacing: 2px;">' + response[0].titulo + '</p>\
                                    </div>\
                                </a>\
                            </li>');

                //Añade el elemento de la lista en la lista desordenada obtenida
                ul.append(li);    
            }
        });
}