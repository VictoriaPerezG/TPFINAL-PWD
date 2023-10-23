<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "./PHPMailer/Exception.php";
require "./PHPMailer/PHPMailer.php";
require "./PHPMailer/SMTP.php";

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Host del tipo de mail que se envia
    $mail->SMTPAuth = true;
    $mail->Username = 'victoria.perez@est.fi.uncoma.edu.ar'; // Correo de origen que se usa para el envio del correo  
    $mail->Password = '43216200'; // Contraseña de aplicación en Gmail
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Utiliza STARTTLS para el protocolo de seguridad
    $mail->Port = 587; 
    $mail->setFrom('victoria.perez@est.fi.uncoma.edu.ar', 'Victoria PG'); //Correo que manda el mail
    $mail->addAddress('vickypgc54@gmail.com'); // Correo de destino 
    $mail->isHTML(true);
    $mail->Subject = 'Aviso'; // Asunto del mail
    $mail->Body = "Mensaje de pueba"; // Mensaje del correo 
    $salida = true;
    // $mail->msgHTML("Hola soy un mensaje"); // Mensaje del correo 
    $mail->send(); // Funcion que envia el correo 
    echo "Correo enviado";
} catch (Exception $e) {
    $salida = false;
    echo "{$mail->ErrorInfo}";
}

// Prueba de un envio de mail de prueba 
/*require "PHPMailer/Exception.php";
require "PHPMailer/PHPMailer.php";
require "PHPMailer/SMTP.php";
 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
$oMail= new PHPMailer();
$oMail->isSMTP();
$oMail->Host="smtp.gmail.com";
$oMail->Port=587;
$oMail->SMTPSecure="tls";
$oMail->SMTPAuth=true;
$oMail->Username="tumail@gmail.com";
$oMail->Password="tupassword";
$oMail->setFrom("tumail@gmail.com","Pepito el que pica papas");
$oMail->addAddress("maildestino@mail.com","Pepito2");
$oMail->Subject="Hola pepe el que pica";
$oMail->msgHTML("Hola soy un mensaje");
 
if(!$oMail->send())
  echo $oMail->ErrorInfo;   */