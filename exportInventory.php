<?php
if(!empty($_POST["id"]))    {
    $header="Host: jamango.io\r\nCookie: jamango_session=".$_COOKIE["jtools_session"];
    $inventoryURL="https://jamango.io/worlds/".$_POST["id"]."/inventory";
    $options=[
        "http"=>[
            "header"=>$header,
            "method"=>"GET"
        ]
    ];
    $context=stream_context_create($options);
    $resultJSON=file_get_contents($inventoryURL,false,$context);
    $result=$http_response_header;
    if($result[0]!="HTTP/1.1 200 OK")  {
        header("Location: /index.php?error2=Couldn't download inventory (".$result[0].")");
        exit();
    }
    $data=$resultJSON;
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
        header("Location: /index.php?error2=Couldn't refresh credentials (".$result[0].")");
        exit();
    }
    $sessionCookie=substr($result[10],28,168);
    $resultJSON=json_decode($resultJSON);
    $csrfToken=$resultJSON->csrf;
    setcookie("jtools_session",$sessionCookie);
    setcookie("jtools_csrf",$csrfToken);
    header("Content-Disposition: attachment; filename=\"".$_POST["id"]."-inventory.json\"; Content-Type: application/json; charset=utf-8");
    echo($data);
    exit();
}else   {
    header("Location: /index.php");
}
?>