<?php

// $to = "nicolaspennesi@gmail.com";
$to = "info@gestionlit.com";

$subject = "Contacto desde Web";
$message = "Nombre: " . $_POST['nombre'];
if (!empty($_POST['telefono'])) {
	$message .= "<br><br>Teléfono: " . $_POST['telefono'];
}
$message .= "<br><br>Email: " . $_POST['email'];
$message .= "<br><br>Mensaje: " . $_POST['mensaje'];


$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
$headers .= "From: Formulario Web <web@gestionlit.com>". "\r\n";
$headers .= "Reply-To: ".$_POST['nombre']." <".$_POST['email'].">". "\r\n";

if( mail($to, $subject, $message, $headers) ) {
	echo "1";
} else {
	echo "Error al enviar mensaje, intente nuevamente más tarde.";
}
