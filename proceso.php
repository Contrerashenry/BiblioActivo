<?php
require("PHPMailer/class.phpmailer.php");
require("PHPMailer/class.smtp.php");

function validar_tel($tel) { 
    $tel = preg_replace("/[^0-9]/","",$tel); 

    if ((strlen($tel) == 11) || (strlen($tel) == 10)) 
        return TRUE; 
    else 
        return FALSE;  
} 

$mail = new PHPMailer;
$mail->IsSMTP();

$to = "henry.contreras077@gmail.com"; // Nuestro correo de contacto

// recogeremos los datos del formulario
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$empresa = $_POST['empresa'];
$telefono = $_POST['telefono'];
$mensaje = nl2br($_POST['mensaje']);
$captcha = sha1($_POST["captcha"]);
$numero_captcha = $_COOKIE["captcha"];

if ( $nombre == "" ) 
        { 
            echo "<div class='alert alert-danger' role='alert'><p>El formato de email es incorrecto</p></div>"; 
        } 
        elseif (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $email)) 
        { 
            echo "<div class='alert alert-danger' role='alert'><p>El formato de email es incorrecto</p></div>"; 
        } 
        elseif(!validar_tel($telefono)) //check for a pattern of 91-0123456789 
        { 
            echo "<div class='alert alert-danger' role='alert'><p>El numero es incorrecto ej: 04146973064</p></div>";  
        } 
        elseif ( $empresa == "" ) 
        { 
            echo "<div class='alert alert-danger' role='alert'><p>Entroduce el nombre de la institucion que representa</p></div>";
        } 
        elseif ( strlen($mensaje) < 10 ) 
        { 
            echo "<div class='alert alert-danger' role='alert'><p>Escribir más de 10 caracteres</p></div>"; 
        } 
        elseif ($captcha != $numero_captcha)
        {
            echo "<div class='alert alert-danger' role='alert'><p>El código no coincide</p></div>";
        }else{
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "ssl";

            //indico el servidor de Gmail para SMTP
            $mail->Host = "smtp.gmail.com";

            //indico el puerto que usa Gmail
            $mail->Port = 465;

            //indico un usuario / clave de un usuario de gmail
            $mail->Username = "contrerashenry07@gmail.com";
            $mail->Password = "hh645142130.";
            
            $mail->setFrom($email, 'Biblio Activo');
            $mail->addAddress($to);
            $mail->Subject = $empresa;
            $mail->isHtml(true);
            $mail->Body = '<strong>'.$nombre.'</strong> le ha contactado desde su web, y le ha enviado el siguiente mensaje: <br><p>'.$email.'</p> <br><p>'.$empresa.'</p> <br><p>'.$telefono.'</p> <br><p>'.$mensaje.'</p>';

            $mail->CharSet = 'UTF-8';
            
            if($mail->Send())
            {
                echo "<div class='btn-enviado msg msg_ok wow animated bounceInRight' role='alert'><span>Su mensaje ha sido enviado  exitosamente</span></div>";
            setcookie("captcha", "", -1);
            ?>
            <script>
            /*Restauramos el formulario y el captcha*/
            document.getElementById("formulario").reset();
            </script>
            <?php
            }
            else
            {
                echo "<div class='alert alert-danger' role='alert'><p>hubo un error al enviar el mensaje por favor contacte al siguiente <i class='fa fa-envelope'></i> <a href='mailto:contacto@biblioactivo.com.ve'>contacto@biblioactivo.com.ve</a></p></div>";
            setcookie("captcha", "", -1);
            
            }

        }

?>

