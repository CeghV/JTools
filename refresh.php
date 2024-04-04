<?php
$header="Host: jamango.io\r\nCookie: jamango_session=".$_COOKIE["jtools_session"]."; jamango_refresh_session=".$_COOKIE["jtools_refresh_session"]."\r\nX-Csrf-Token: ".$_COOKIE["jtools_csrf"];
$refreshURL="https://jamango.io/refresh";
$options=[
    "http"=>[
        "header"=>$header,
        "method"=>"POST"
    ]
];
$context=stream_context_create($options);
$resultJSON=file_get_contents($refreshURL,false,$context);
$result=$http_response_header;
if($result[0]!="HTTP/1.1 200 OK")  {
    header("Location: /index.php?error3=Couldn't refresh credentials (".$result[0].")");
    exit();
}
$sessionCookie=substr($result[11],28,168);
$resultJSON=json_decode($resultJSON);
$csrfToken=$resultJSON->csrf;
setcookie("jtools_session",$sessionCookie);
setcookie("jtools_csrf",$csrfToken);
header("Location: /index.php");
?>