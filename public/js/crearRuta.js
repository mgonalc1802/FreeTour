//Espera a que la página cargue
$(function()
{
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
        fechaSalida.setDate(fechaSalida.getDate() + 7);

        //Crea datepicker que tenga una semana después de salida y máximo dos meses
        $("#llegada").datepicker({minDate: fechaSalida, maxDate: "+2M"});
    });
})