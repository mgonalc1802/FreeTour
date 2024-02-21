//Espera a que la página cargue
$(function()
{
    var crear = '<a class=" action-new btn btn-primary" href="/admin?routeName=rutas" data-action-name="new"><span class="action-label">Add Ruta</span></a>';
                      
    //Obtiene el header de la plantilla de easyAdmin
    $(".content-header-title h1").append('Rutas');
    $(".page-actions").append(crear);

    //Oculta los botones
    $(".global-actions").css("visibility", "hidden");

    //Obtiene el div
    var ruta = $("#verRuta");

    //Lo introduce en el contenedor de easyadmin
    $(".content").append(ruta);

    traeRutas();
});

function traeRutas()
{
    //Llamada AJAX que se encarga de insertar Ruta
    $.ajax(
    {
        url: "/API/rutas",
        type: 'GET',
        dataType: 'json',
        contentType: 'application/json', 
        processData: false,
        success: function (response) 
        {
            //Obtiene el contenido html necesario
            var tablaRutas = $("tbody");

            //Recorre el array que obtiene a través de la API
            for(let i = 0; i < response.length; i++)
            {
                //Crea una fila elemento para tbody
                var tr = $('<tr data-id="' + response[i].id + '">');

                //Crea una columna para la fila
                var tdTitulo = $('<td data-column="titulo" data-label="Título" class=" text- field-text" dir="ltr">\
                                    <span title="' + response[i].titulo + '">' + response[i].titulo + '</a>\
                                 </td>');
                
                var tdFechaComienzo = $('<td data-column="fechaInicio" data-label="Fecha Inicio" class=" text- field-text" dir="ltr">\
                                        <span title="' + response[i].fechaComienzo + '">' + response[i].fechaComienzo + '</span>\
                                    </td>');

                var tdFechaFin= $('<td data-column="fechaFin" data-label="Fecha Fin" class=" text- field-text" dir="ltr">\
                                        <span title="' + response[i].fechaFin + '">' + response[i].fechaFin + '</span>\
                                    </td>');

                var tdAforo= $('<td data-column="aforo" data-label="Aforo" class=" text- field-text" dir="ltr">\
                                    <span title="' + response[i].aforo + '">' + response[i].aforo + '</span>\
                                </td>');
                
                var tdFoto = $('<td data-column="url_foto" data-label="Url Foto" class=" text-center field-image" dir="ltr">\
                                    <a href="#" class="ea-lightbox-thumbnail" data-ea-lightbox-content-selector="#ea-lightbox-01HQ2WXTF508R05P0KY6XS6TK2-1">\
                                        <img src="/images/ruta/' + response[i].urlFoto + '" class="img-fluid">\
                                    </a>\
                                    <div id="ea-lightbox-01HQ2WXTF508R05P0KY6XS6TK2-1" class="ea-lightbox">\
                                        <img src="/images/ruta/' + response[i].urlFoto + '">\
                                    </div>\
                                </td>');

                var tdAcciones = $('<td class="actions actions-as-dropdown">\
                                        <div class="dropdown dropdown-actions">\
                                            <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\
                                                <svg xmlns="http://www.w3.org/2000/svg" height="21" width="21" fill="none" viewBox="0 0 24 24" stroke="currentColor">\
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>\
                                                </svg>\
                                            </a>\
                                            <div class="dropdown-menu dropdown-menu-right">\
                                                <a class="dropdown-item action-edit" href="/modificarRuta/' + response[i].id + '" data-action-name="edit"><span class="action-label">Edit</span></a>\
                                                <a class="dropdown-item action-delete text-danger" onclick = "borrarRuta(' + response[i].id + ')" data-action-name="delete"><span class="action-label">Delete</span></a>\
                                            </div>\
                                        </div>\
                                    </td>');

                //Añade al tr sus datos necesarios
                tr.append(tdTitulo);
                tr.append(tdFechaComienzo);
                tr.append(tdFechaFin);
                tr.append(tdAforo);
                tr.append(tdFoto);
                tr.append(tdAcciones);

                //Añade al select la opción
                tablaRutas.append(tr);
            }       
        }
    });
}

function borrarRuta(id)
{
    $.ajax(
        {
            url: "/API/borrarRuta/" + id,
            type: 'GET',
            dataType: 'json',
            contentType: 'application/json', 
            processData: false,
            success: function (response) 
            {
                alert(response);
                window.location.href = "/admin?routeName=verRutas";
            }
        });
}