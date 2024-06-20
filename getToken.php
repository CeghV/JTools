<?php
$header="Host: jamango.io\r\nCookie: jamango_session=".$_COOKIE["jtools_session"]."; jamango_refresh_session=".$_COOKIE["jtools_refresh_session"]."\r\nX-Csrf-Token: ".$_COOKIE["jtools_csrf"];
$refreshURL="https://jamango.io/ibs";
$options=[
    "http"=>[
        "header"=>$header,
        "method"=>"POST"
    ]
];
$context=stream_context_create($options);
$resultJSON=file_get_contents($refreshURL,false,$context);
$resultJSON=json_decode($resultJSON);
$result=$http_response_header;
if($result[0]!="HTTP/1.1 200 OK")  {
    header("Location: /index.php?error=Couldn't get token (".$result[0].")");
    exit();
}
setcookie("token",$resultJSON->token);
header("Location: /index.php");
?>