//Espera a que la página cargue
$(function()
{
    var gallery = $( "#gallery" );

    traerItems(gallery);

    // There's the gallery and the trash
    var trash = $( "#itemSelec" );    

    // Let the trash be droppable, accepting the gallery items
    trash.droppable(
    {
        accept: "#gallery > li",
        classes: {
            "ui-droppable-active": "ui-state-highlight"
        },
        drop: function( event, ui ) {
            deleteImage( ui.draggable );
        }
    });

    // Let the gallery be droppable as well, accepting items from the trash
    gallery.droppable(
    {
        accept: "#trash li",
        classes: {
            "ui-droppable-active": "custom-state-active"
        },
        drop: function( event, ui ) {
            recycleImage( ui.draggable );
        }
    });

    // Image deletion function
    var recycle_icon = "<a href='link/to/recycle/script/when/we/have/js/off' title='Recycle this image' class='ui-icon ui-icon-refresh'>Recycle image</a>";
    function deleteImage( $item ) 
    {
        $item.fadeOut(function() 
        {
            var $list = $( "ul", trash ).length ?
            $( "ul", trash ) :
            $( "<ul class='gallery ui-helper-reset'/>" ).appendTo( trash );

            $item.find( "a.ui-icon-trash" ).remove();
            $item.append( recycle_icon ).appendTo( $list ).fadeIn(function() {
            $item
                .animate({ width: "48px" })
                .find( "img" )
                .animate({ height: "36px" });
            });
        });
    }

    // Image recycle function
    var trash_icon = "<a href='link/to/trash/script/when/we/have/js/off' title='Delete this image' class='ui-icon ui-icon-trash'>Delete image</a>";
    function recycleImage( $item ) 
    {
        $item.fadeOut(function() 
        {
            $item
            .find( "a.ui-icon-refresh" )
            .remove()
            .end()
            .css( "width", "96px")
            .append( trash_icon )
            .find( "img" )
            .css( "height", "72px" )
            .end()
            .appendTo( gallery )
            .fadeIn();
        });
    }

    // Image preview function, demonstrating the ui.dialog used as a modal window
    function viewLargerImage( $link ) 
    {
        var src = $link.attr( "href" ),
            title = $link.siblings( "img" ).attr( "alt" ),
            $modal = $( "img[src$='" + src + "']" );

        if ( $modal.length ) 
        {
            $modal.dialog( "open" );
        }
        else 
        {
            var img = $( "<img alt='" + title + "' width='384' height='288' style='display: none; padding: 8px;' />" )
            .attr( "src", src ).appendTo( "body" );
            setTimeout(function()
            {
                img.dialog(
                {
                    title: title,
                    width: 400,
                    modal: true
                });
            }, 1 );
        }
    }

    // Resolve the icons behavior with event delegation
    $( "ul.gallery > li" ).on( "click", function( event ) 
    {
        var $item = $( this ),
            $target = $( event.target );

        if ( $target.is( "a.ui-icon-trash" ) ) 
        {
            deleteImage( $item );
        } 
        else if ( $target.is( "a.ui-icon-zoomin" ) ) 
        {
            viewLargerImage( $target );
        } 
        else if ( $target.is( "a.ui-icon-refresh" ) )
        {
            recycleImage( $item );
        }

        return false;
    });

    //Indica que el textarea sea un richText
    $('.textArea').richText();

    //Indica que el select que muestra los items es draggable
    $("#items").draggable(
    {
        helper: "clone",
        revert: "invalid"
    });

    //Indica que el select vacío es droppable
    $("#items").droppable(
    {
        accept: "#selectDraggable option",
        drop: function (event, ui) 
        {
            $(this).append(ui.helper.children());
        }
    });

    //Llama a un método para asignar datePicker personalizados
    personalizaDatePicker();

    //Obtiene el botón
    var enviar = $("#enviar");
    var buscar = $("#buscarMapa");

    

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
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', 
                {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                L.marker([latitud, longitud]).addTo(map).openPopup();
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
            $.each(data, function (index, option) {
                console.log(option.titulo)
                //Crea la nueva lista
                var liElement = $('<li class="ui-widget-content ui-corner-tr ui-draggable ui-draggable-handle"></li>');

                //Añade la cabecera
                liElement.append('<h5 class="ui-widget-header">' + option.titulo + '</h5>');

                //Añade la imagen
                liElement.append('<img src="' + option.foto + '" alt="' + option.titulo + '" width="96" height="72">');
                
                //Añade el borrar link
                liElement.append('<a href="' + option.foto + '" title="View larger image" class="ui-icon ui-icon-zoomin">View larger</a>');

                //Añade el borrar link
                liElement.append('<a href="link/to/itemSelec/script/when/we/have/js/off" title="Delete this image" class="ui-icon ui-icon-itemSelec">Delete image</a>');

                // Append the list item to the provided element
                elemento.append(liElement);
            });

            //Hace que los datos de galeria sean droppables
            $( "li", elemento ).draggable(
            {
                cancel: "a.ui-icon", // clicking an icon won't initiate dragging
                revert: "invalid", // when not dropped, the item will revert back to its initial position
                containment: "document",
                helper: "clone",
                cursor: "move"
            });
        },
        error:function(err) //Respuesta si da error
        {
            //Muestra los datos por consola
            console.log(err); 
        }
    })
}