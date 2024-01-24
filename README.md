#Diseño de la Interfaz de una Web de Free Tours
## María Dolores González Alcalá
### Requerimientos:
Existirán 3 roles distintos sobre el logueo: <br>
    • Administrador. <br>
    • Guía turístico. <br>
    • Cliente. <br>
Todos los nuevos registros serán de tipo “Cliente”. <br>
Sólo el admin podrá designar nuevos “Guías”.<br>
La página principal será la vista para usuarios no registrados, que será la misma que la propia <br>
de “Clientes” solo que a la hora de reservar una Ruta no se podrá hacer hasta loguearse.<br>
### Funcionalidades del rol de Administrador:<br>
Un administrador podrá:<br>
    • Crear rutas.<br>
    • Asignar rutas creadas a los guías disponibles. Habría que controlar si los guías están <br>
      disponibles ese día a esa hora (sólo se ha de comprobar que ese guía no está en otra <br>
      visita ese día a esa hora, la mejor forma de comprobarlo es no mostrarlo en la tabla de <br>
      guías una vez se vaya a realizar la asignación de guía a una ruta)<br>
    • Cancelar una ruta existente.<br>
Definimos en detalle las funcionalidades:<br>
####    • CREAR RUTAS:<br>
Para crear una ruta se podrá insertar los campos básicos:<br>
    ✓ Título de la ruta.<br>
    ✓ Localidad de la ruta.<br>
    ✓ Descripción de la ruta.<br>
    ✓ Foto general de la ruta.<br>
Además, una ruta se cómo de Items a visitar, los cuales se podrán definir, cada item se <br>
compondrá a su vez de:<br>
    ✓ Título del item.<br>
    ✓ Descripción del item.<br>
    ✓ Foto del item.<br>
    ✓ Geolocalización del item.<br>
A su vez, habría que introducir en qué periodo se va a realizar la ruta y las fechas y <br>
horarios en los que está disponible para poder reservarse.<br>
####    • ASIGNAR RUTA A UN GUÍA:<br>
Una vez que una ruta sea solicitada por al menos 1 persona se creará una instancia de <br>
la misma a la cual habrá que asignar a 1 guía. La asignación de ruta a un guía se hará <br>
de forma manual, para poder decidir sobre los guías disponibles quién lo podría hacer <br>
mejor.<br>
Para ello, se mostrará un listado solamente de los guías disponibles en esa fecha y a <br>
esa hora, reservando esa fecha para ese guía y quitándolo de su disponibilidad.<br>
(NOTA: Aunque la ruta se cree a partir de la primera persona, si no hay un mínimo de <br>
10 personas puede que la ruta no se realice. Un guía como máximo acompañará a 20 <br>
personas, con lo que, si a una ruta se inscriben 21 personas, se crearían 2 instancias, <br>
siempre ordenando a los clientes en función de su fecha de reserva)<br>
#### • CANCELAR UNA RUTA:<br>
Si una ruta no llega al mínimo de 10 personas puede que no se realice, pero eso <br>
dependerá del administrador, que será el único en última instancia que podrá <br>
decidirlo.<br>
Si el administrador decide cancelar una ruta, se le enviará un correo a todos los <br>
clientes inscritos para informarles y al guía, dejando esa fecha y hora libre para poder <br>
realizar otras visitas.<br>
(NOTA: Siempre que una ruta esté en peligro de no poder realizarse por su número <br>
de participantes, se les enviará correos a los clientes para indicarles que su ruta <br>
“peligra” debido a la falta de participantes)<br>
### Funcionalidades del rol de Guía Turístico:<br>
Un guía podrá:<br>
    • Ver sus rutas asignadas.<br>
    • Pasar lista en las rutas a las que se le asignen.<br>
    • Hacer un informe de una ruta, una vez se termine.<br>
Definimos en detalle las funcionalidades:<br>
####    • VER RUTAS ASIGNADAS:<br>
Aparecerá un listado con las rutas que se vayan asignando ordenadas por fecha (arriba <br>
la más próxima a ocurrir).
A las nuevas rutas asignadas se les pondrá un distintivo para informar de que es una <br>
nueva ruta asignada.<br>
(NOTA: Hay que llevar el control de que a un guía no se le pueden asignar 2 rutas en <br>
una misma fecha y hora)<br>
#### • PASAR LISTA EN UNA RUTA:<br>
Cuando se asocia una ruta a un guía, el guía ya puede entrar a ella y ver las personas <br>
inscritas.<br>
(NOTA: Cuidado, porque un mismo cliente podría inscribir hasta un máximo de 8<br>
personas, bajo su misma identidad)<br>
Una vez se produzca la visita, el guía podrá pasar lista y marcar qué personas acuden y <br>
cuales no, para llevar un control de asistencia real.<br>
#### • INFORME DE LA RUTA:<br>
Dentro de cada ruta asignada, además de la funcionalidad de pasar lista, también <br>
existirá la opción de realizar un informe, el cual permanecerá deshabilitado hasta la <br>
hora prevista de finalización de la ruta.<br>
El informe contendrá los campos de:<br>
    ✓ Insertar una imagen de grupo.<br>
    ✓ Añadir un texto de observaciones.<br>
    ✓ Añadir el dinero recaudado en la ruta.<br>
Mientras el guía no rellene el informe, la ruta aparecerá marcada en un color que la <br>
distinga del resto como realizada, pero sin informe enviado.<br>
Una vez se haya enviado el informe, la ruta se habrá finalizado completamente y <br>
desaparecerá de la lista del guía.<br>
### Funcionalidades del rol de Cliente:<br>
Un cliente podrá:<br>
    • Ver las rutas disponibles.<br>
    • Reservar una ruta.<br>
    • Valorar una ruta realizada.<br>
    • Cancelar una reserva existente.<br>
Definimos en detalle las funcionalidades:<br>
    • VER RUTAS DISPONIBLES:<br>
Esta será nuestra página principal, donde por defecto aparecerán las rutas más <br>
visitadas o las mejor valoradas, en función de un pequeño filtro que deberá existir.<br>
En la web habrá un buscador de rutas por localidad y/o fecha.<br>
Si buscamos sólo por localidad, se deberá introducir la localidad y aparecerán todas las <br>
rutas previstas en cualquier fecha para esa localidad.<br>
Si además de la localidad agregamos el filtro de la fecha, se mostrarán todas las rutas <br>
de esa localidad en esa fecha.<br>
No se podrá buscar sólo por fecha.<br>
Una vez se produzca una búsqueda, los resultados se podrán seguir filtrando por <br>
primero las rutas más visitadas o primero las mejor valoradas.<br>
Se mostrará un listado de rutas coincidentes con la búsqueda mostrando:<br>
    ✓ Carrusel de fotos de la ruta.<br>
    ✓ Título de la ruta.<br>
    ✓ Descripción de la ruta.<br>
    ✓ Fecha y hora de la ruta.<br>
    ✓ Valoración media de la ruta y número de visitas realizadas.<br>
Una vez que pulsemos sobre una ruta, se ampliará la información de la misma <br>
mostrando los ítems de la ruta, ordenados desde el punto de encuentro, al de fin, con <br>
información de cada uno, además de toda la anterior información mostrada en la vista <br>
previa.<br>
Por supuesto, aparecerá un pequeño formulario para reservar ruta, indicando el <br>
número total de asistentes (máximo 8).<br>
Este formulario no se mostrará para los usuarios no registrados, pero se mostrará un <br>
mensaje que indique que se registre para poder hacer una reserva.<br>
#### • RESERVAR RUTA:<br>
Cuando entramos sobre una ruta, podremos hacer una reserva tal cual se ha descrito <br>
antes, indicando el número de participantes y dando al botón de reservar.<br>
En ese momento, se le enviará un email de confirmación al usuario recordando la ruta, <br>
fecha y hora de la reserva, además de dando la posibilidad de que se guarde la cita en <br>
su Google Calendar.<br>
Una vez realizada una reserva de ruta, se guardará en el apartado de “Mis Reservas”, <br>
desde donde se podrá modificar en todo momento la reserva, en cuanto al número de <br>
plazas reservadas o la cancelación de la misma.<br>
#### • VALORAR RUTA REALIZADA:<br>
Una vez finalice una ruta que se haya realizado, la ruta pasará a la sección del cliente <br>
“Mis rutas”, donde el cliente tendrá un histórico de todas las rutas realizadas con toda <br>
la información de la misma, la cual a su término podrá valorar de 1 a 5 estrellas según <br>
su agrado, además de poder dejar un comentario escrito.<br>
#### • CANCELAR RUTA:<br>
Tal y como se ha visto antes, en la sección del cliente registrado “Mis reservas”, se <br>
podrá gestionar la modificación del número de usuarios a reservar y la cancelación de <br>
la ruta.<br>
(NOTA: cada vez que exista una modificación en la reserva, se notificará por email)<br>