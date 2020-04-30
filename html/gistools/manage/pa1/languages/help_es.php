<?php
#Application name: PhpCollab
#Status page: 2
#Path by root: ../languages/help_es.php

//translator(s): 
$help["setup_mkdirMethod"] = "Si safe-mode esta activado, usted necesita crear una cuenta FTP que est� autorizada para crear carpetas con administraci�n de archivos.";
$help["setup_notifications"] = "Notificaciones v�a correo electr�nico (Asignaci�n de tareas, nuevos temas, cambios en tareas...)<br>Se requiere smtp/sendmail valido.";
$help["setup_forcedlogin"] = "Si es falso, deshabilita que se autorice la entrada desde un url que contenga el login/password incluido.";
$help["setup_langdefault"] = "Escoja el idioma que se seleccionar� como predeterminado en el momento de logearse, o deje en blanco para que sea autodetectado por el navegador.";
$help["setup_myprefix"] = "Ingrese este valor si usted tiene tablas en la base de datos con el mismo nombre.<br><br>assignments<br>bookmarks<br>bookmarks_categories<br>calendar<br>files<br>logs<br>members<br>notes<br>notifications<br>organizations<br>phases<br>posts<br>projects<br>reports<br>sorting<br>subtasks<br>support_posts<br>support_requests<br>tasks<br>teams<br>topics<br>updates<br><br>Deje en blanco si no quiere utilizar un prefijo.";
$help["setup_loginmethod"] = "M�todo para almacenar passwords en la base de datos.<br>Seleccione &quot;Crypt&quot; si quiere que la autenticaci�n por el m�todo CVS y htaccess funcionen (Si autenticaci�n y/o htaccess est�n activados).";
$help["admin_update"] = "Respetar estrictamente el orden indicado para actualizar su versi�n<br>1. Edite sus preferencias (sustituya con los nuevos par�metros)<br>2. Edite la base de datos (actualice de acuerdo con su versi�n anterior)";
$help["task_scope_creep"] = "Diferencia en d�as entre los la fecha de entrega y la fecha de completada (Negrilla si es positiva)";
$help["max_file_size"] = "M�ximo peso de un archivo permitido para ser publicado";
$help["project_disk_space"] = "Peso total de los archivos publicados en el proyecto";
$help["project_scope_creep"] = "Diferencia en d�as entre los la fecha de entrega y la fecha de completada (Negrilla si es positiva). Total para todas las tareas.";
$help["mycompany_logo"] = "Publique el logo de su compa��a. Aparece en el encabezado, en vez de el t�tulo en texto";
$help["calendar_shortname"] = "T�tulo que aparece en la vista del calendario mensual. Obligatorio";
$help["user_autologout"] = "Tiempo, en segundos para ser desconetado del sistema si no hay actividad (Time Out). 0 para desactivar esta opci�n.";
$help["user_timezone"] = "Seleccione su zona de tiempo global (GMT)";
//2.4
$help["setup_clientsfilter"] = "Filter to see only logged user clients";
$help["setup_projectsfilter"] = "Filter to see only the project when the user are in the team";
?>