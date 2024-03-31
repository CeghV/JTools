<?php
if(!empty($_POST["id"]) and !empty($_POST["type"])) {
    $header="Host: jamango.io\r\nCookie: jamango_session=".$_COOKIE["jtools_session"];
    $worldURL="https://jamango.io/worlds/download?identifier=".$_POST["id"];
    $options=[
        "http"=>[
            "header"=>$header,
            "method"=>"GET"
        ]
    ];
    $context=stream_context_create($options);
    $resultJSON=file_get_contents($worldURL,false,$context);
    $result=$http_response_header;
    if($result[0]!="HTTP/1.1 200 OK")  {
        header("Location: /index.php?error1=Couldn't download world (".$result[0].")");
        exit();
    }
    $resultJSON=json_decode($resultJSON);
    $downloadURL=$resultJSON->url;
    $data=file_get_contents($downloadURL);
    if($data==false)    {
        header("Location: /index.php?error1=Couldn't download world from AWS (".$result[0].")");
        exit();
    }
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
        header("Location: /index.php?error1=Couldn't refresh credentials (".$result[0].")");
        exit();
    }
    $sessionCookie=substr($result[10],28,168);
    $resultJSON=json_decode($resultJSON);
    $csrfToken=$resultJSON->csrf;
    setcookie("jtools_session",$sessionCookie);
    setcookie("jtools_csrf",$csrfToken);
    if($_POST["type"]=="blob")  {
        header("Content-Disposition: attachment; filename=\"".$_POST["id"].".bin\"; Content-Type: application/octet-stream; charset=utf-8");
        echo($data);
    }   elseif($_POST["type"]=="json")  {
        $data=gzuncompress($data);
        header("Content-Disposition: attachment; filename=\"".$_POST["id"].".json\"; Content-Type: application/json; charset=utf-8");
        echo($data);
    }
}   else{
    header("Location: /index.php");
}
?>