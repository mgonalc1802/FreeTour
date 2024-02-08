//Espera a que la página cargue
$(function()
{
    var crearOther = '<button class="crearOther btn btn-secondary" type="submit" value="crearOther" data-action-name="crearOther" form="new-Ruta-form">\
                        <span class="btn-label"><span class="action-label">Crear y Añadir Otro</span></span>\
                      </button>';

    var crearSalir = '<button class="crearSalir btn btn-primary action-save" type="submit" name="ea[newForm][btn]" value="saveAndReturn" data-action-name="saveAndReturn" form="new-User-form">\
                        <span class="btn-label"><span class="action-label">Crear</span></span>\
                      </button>';
                      
    //Obtiene el header de la plantilla de easyAdmin
    $(".content-header-title h1").append('Rutas');
    $(".page-actions").append(crearSalir);
    $(".page-actions").append(crearOther);

    //Obtiene el div
    var ruta = $("#ruta");

    //Lo introduce en el contenedor de easyadmin
    $(".content").append(ruta);



    
    //-----------VARIABLES-----------------
    //Obtiene el boton buscar
    var buscar = $("#buscarMapa");

    //Obtiene el elemento html filtros que es un select
    var filtros = $("#filtros");

    //Obtiene el elemento html gallery que es un ul
    var gallery = $( "#gallery" );

    //Obtiene el elemento html filtra que es un input de tipo text
    var filtra = $("#filtra");

    //Obtiene el boton filtrar
    var btnFiltrar = $("#filtrar");

    //Donde se guarda la galería
    var trash = $( "#itemSelec" );

    //Crea una variable para guardar el dato de filtros
    var valueFil = filtros.val();

    //Obtiene el botón
    var enviar = $(".crearSalir");

    //Obtiene el botón que permite cambiar de página
    var siguiente = $("#siguiente");

    //Obtiene el botón que permite retroceder de página
    var anterior = $("#anterior");

    siguiente.click(function (ev)
    {
        //Previene el submit que realiza por defecto
        ev.preventDefault();

        //Hace invisible la primera parte
        $("#parte1").css("display", "none");

        //Hace visible la segunda parte
        $("#parte2").css("display", "block");
    });

    anterior.click(function (ev)
    {
        //Previene el submit que realiza por defecto
        ev.preventDefault();

        //Hace visible la primera parte
        $("#parte1").css("display", "block");

        //Hace invisible la segunda parte
        $("#parte2").css("display", "none");
    });

    $( "#map" ).dialog(
    {
        autoOpen: false,
        modal: true,
        show: 
            {
                effect: "blind",
                duration: 1000
            },
        hide: 
            {
                effect: "explode",
                duration: 1000
            },
        width: 1000, 
        height: 600 
    });

    //Si escribimos la ruta en el input, devuelve las coordenadas sin ver el mapa
    $("#indicaRuta").on("change", function()
    {
        //Llama al método para devolver las coordenadas.
        devuelveCoordenadas($("#indicaRuta").val())
    })

    //Ejecuta su evento click
    buscar.click(function(ev)
    {
        //Previene el submit que realiza por defecto
        ev.preventDefault();
        
        //Llama a una función que genera el mapa
        generaMapa();
    });

    //Indica que el textarea sea un richText
    $('.textArea').richText();

    //Llama a un método para asignar datePicker personalizados
    personalizaDatePicker();

    //Cuando filtros cambie
    filtros.on("change", function()
    {
        //Cambia el valor de la variable
        valueFil = filtros.val();
    })

    //Acción de click del boton
    btnFiltrar.click(function(ev)
    {
        //Previene el submit que hace por defecto
        ev.preventDefault();

        //Se encarga de vaciar los items
        $("#itemSelec ul").remove();

        //Condición dependiendo del valueFil
        traerItems(gallery, valueFil, filtra.val());

        //Vacía el input 
        filtra.val("");
    }) 

    //Método que muestra donde se sueltan los items.
    soltarItems(trash, gallery);
    
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

    //Evento del click
    enviar.click(function(ev)
    {
        //Previene el evento submit que tiene por defecto
        ev.preventDefault();

        //Obtiene los datos del formulario
        var titulo = $("#titulo").val();
        var descripcion = $("#descripcion").val();   
        var fechaComienzo = $("#salida").val().split('/').reverse().join('-');
        var fechaFin = $("#llegada").val().split('/').reverse().join('-');
        var aforo = parseInt($("#aforo").val());
        var foto = subirFoto();
        var coordenadas = $("#coordenadaInicio").val();
        var descripcion = $("#descripcion").val();

        //Genera el json
        var json = 
        {
            "titulo": titulo,
            "fechaInicio": fechaComienzo,
            "fechaFin": fechaFin,
            "aforo": aforo,
            "descripcion": descripcion,
            "url_foto": foto,
            "coordenadas": coordenadas
        };

        //Llama al método insertarRuta
        insertarRuta(json);

        //Obtiene la lista de elementos dentro del div
        var itemsSelec = $('#itemSelec li');

        //Indica el tamanio del array itemsSelec
        var longitud = itemsSelec.length;

        //Array para guardar los id de los Items guardados
        const idItems = [];

        //Bucle que guarda los id de los item
        for(let i = 0; i < longitud; i++)
        {
            idItems[i] = itemsSelec[i].dataset.id;
        }

    })
})

function insertarRuta(json) 
{
    //Llamada AJAX que se encarga de insertar Ruta
    $.ajax(
    {
        url: "/API/crearRuta",
        type: 'POST',
        dataType: 'json',
        data: JSON.stringify(json), 
        contentType: 'application/json', 
        processData: false,
        success: function (response) 
        {
            console.log(response);
        }
    });
}

function soltarItems(trash, gallery)
{
    // Let the trash be droppable, accepting the gallery items
    trash.droppable(
    {
        accept: "#gallery > li",
        classes: {
            "ui-droppable-active": "ui-state-highlight"
        },
        drop: function( event, ui ) 
        {
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
}

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

function devuelveCoordenadas(direccion)
{
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
            } 
        },
        error: function () 
        {
            alert("Error al obtener las coordenadas. Por favor, inténtalo de nuevo.");
        }
    });
}


function generaMapa()
{
    //Abre un modal con el mapa
    $( "#map" ).dialog( "open" );

    //Obtiene el input que indica la dirección de la ruta
    var direccion = $("#indicaRuta").val();

    if(direccion == "")
    {
        //Inicializa el mapa de Leaflet, generando L que es una variable global.
        var map = L.map('map').setView([37.77, -3.79], 15);

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
            $("#coordenadaInicio").val(e.latlng.lat + ", " + e.latlng.lng);
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
    else
    {
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

                    //Abre el mapa con el marcador en las coordenadas
                    L.marker([latitud, longitud]).addTo(map).openPopup();

                    //Comprueba el click del boton cerrar del modal
                    $(".ui-dialog-titlebar-close").click( function ()
                    {
                        //Cierra el mapa
                        map.off();

                        //Borra el mapa
                        map.remove();
                    });
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
}

function subirFoto()
{
    //Crea el formFata        
    var formData = new FormData();

    //Obtiene la imagen
    var files = $('#image')[0].files[0];
    
    //Añade la imagen al formData
    formData.append('file', files);
    
    //Llamada AJAX
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

    //Devuelve el nombre del archivo
    return files.name;
}


function traerItems(elemento, filtro, valor)
{
    //Llamada AJAX para obtener los items
    $.ajax(
    {
        url: "API/item/" + filtro + "/" + valor, //Indica la URL
        method: "get", //Indica el método
        dataType: "JSON", //Indica como viene
        cache: false,
        success:function(data) //Respuesta si obtiene los datos necesarios
        {
            $.each(data, function (index, option) 
            {
                //Crea un nuevo elemento de la lista
                var liElement = $('<li class="ui-widget-content ui-corner-tr ui-draggable ui-draggable-handle" data-id = "' + option.id + '"></li>');

                //Añade la cabecera
                liElement.append('<h5 class="ui-widget-header">' + option.titulo + '</h5>');

                //Añade la imagen
                liElement.append('<img src="images/items/' + option.foto + '" alt="' + valor + '" width="96" height="72">');
                
                //Añade el borrar link
                liElement.append('<a href="images/items/' + option.foto + '" title="View larger image" class="ui-icon ui-icon-zoomin">View larger</a>');

                // Append the list item to the provided element
                elemento.append(liElement);
            });

            //Hace que los datos de galeria sean droppables
            $( "li", elemento ).draggable(
            {
                cancel: "a.ui-icon", // clicking an icon won't initiate dragging
                revert: "invalid", // Cuando no es dropeado, vuelve a la misma posición
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