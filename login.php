<?php
if(!empty($_POST["login-email"]) and !empty($_POST["login-password"]))   {
    $header="Host: jamango.io\r\nContent-Type: application/json";
    $loginURL="https://jamango.io/login";
    $email=$_POST["login-email"];
    $password=$_POST["login-password"];
    $data=["email"=>$email,"password"=>$password];
    $options=[
        "http"=>[
            "header"=>$header,
            "method"=>"POST",
            "content"=>json_encode($data)
        ]
    ];
    $context=stream_context_create($options);
    $resultJSON=file_get_contents($loginURL,false,$context);
    $result=$http_response_header;
    if($result[0]!="HTTP/1.1 200 OK")  {
        header("Location: /index.php?error=Couldn't log in to jamango (".$result[0].")");
        exit();
    }
    $sessionCookie=substr($result[11],28,168);
    $refreshCookie=substr($result[12],36,168);
    $resultJSON=json_decode($resultJSON);
    $csrfToken=$resultJSON->csrf;
    setcookie("jtools_session",$sessionCookie);
    setcookie("jtools_csrf",$csrfToken);
    setcookie("jtools_refresh_session",$refreshCookie);
    header("Location: /index.php");
}else{
    header("Location: /index.php?error=Missing email or password!");
}
?>