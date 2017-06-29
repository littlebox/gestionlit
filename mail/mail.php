<?php

// $to = "nicolaspennesi@gmail.com";
$to = "info@gestionlit.com";
$from = "Formulario Web <web@gestionlit.com>";

$subject = "Contacto desde Web";
$message = "Nombre: " . $_POST['nombre'];
if (!empty($_POST['telefono'])) {
	$message .= "<br><br>Teléfono: " . $_POST['telefono'];
}
$message .= "<br><br>Email: " . $_POST['email'];
$message .= "<br><br>Mensaje: " . $_POST['mensaje'];

// Si no envía CV
if (empty($_POST['cv'])) {
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
	$headers .= "From: ".$from."\r\n";
	$headers .= "Reply-To: ".$_POST['nombre']." <".$_POST['email'].">". "\r\n";
	$mail_sent = mail($to, $subject, $message, $headers);
} else {
	$file = $_FILES['file']['tmp_name'];
    $mail_sent = mail_attachment($to, $subject, $message, $from, $file);
}

if($mail_sent) {
	echo "1";
} else {
	echo "Error al enviar mensaje, intente nuevamente más tarde.";
}

function mail_attachment($to, $subject, $message, $from, $file) {
	// $file should include path and filename
	$filename = $_FILES['file']['name'];
	$file_size = filesize($file);
	$content = chunk_split(base64_encode(file_get_contents($file))); 
	$uid = md5(uniqid(time()));
	$from = str_replace(array("\r", "\n"), '', $from); // to prevent email injection

	// carriage return type (RFC)
	$eol = "\r\n";

	// main header (multipart mandatory)
	$headers = "From: " . $from . $eol;
	$headers .= "MIME-Version: 1.0" . $eol;
	$headers .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"" . $eol;
	$headers .= "Content-Transfer-Encoding: 7bit" . $eol;
	$headers .= "This is a MIME encoded message." . $eol;

	// message
	$body = "--" . $uid . $eol;
	$body .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
	$body .= "Content-Transfer-Encoding: 8bit" . $eol;
	$body .= $message . $eol;

	// attachment
	$body .= "--" . $uid . $eol;
	$body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
	$body .= "Content-Transfer-Encoding: base64" . $eol;
	$body .= "Content-Disposition: attachment" . $eol;
	$body .= $content . $eol;
	$body .= "--" . $uid . "--";

	return mail($to, $subject, $body, $headers);
}
