<?php
if(!empty($_POST["name"]) and is_uploaded_file($_FILES["world"]["tmp_name"]))    {
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,"https://jamango.io/worlds/save");
    curl_setopt($ch,CURLOPT_POST,1);
    $data=file_get_contents($_FILES["world"]["tmp_name"]);
    if(str_contains($_FILES["world"]["name"],".json")) {
        $data=gzcompress($data);
    }
    $blob=$data;
    $inventory="";
    if(is_uploaded_file($_FILES["inventory"]["tmp_name"]))  {
        $data=file_get_contents($_FILES["inventory"]["tmp_name"]);
        $inventory=$data;
    }
    $world=new CURLStringFile($blob,"blob","application/octet-stream");
    $head=array(
        "file"=>$world,
        "generator"=>$_POST["generator"],
        "inventory"=>$inventory,
        "file_name"=>$_POST["name"]
    );
    if(!empty($_POST["id"]))    {
        $head["world_id"]=$_POST["id"];
    }
    curl_setopt($ch,CURLOPT_POSTFIELDS,$head);
    curl_setopt($ch,CURLOPT_COOKIE,"jamango_session=".$_COOKIE["jtools_session"]);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLINFO_HEADER_OUT,true);
    curl_setopt($ch,CURLOPT_HTTPHEADER,array(
        "X-Csrf-Token: ".$_COOKIE["jtools_csrf"]
    ));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

    $response=curl_exec($ch);
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
    $sessionCookie=substr($result[10],28,168);
    $resultJSON=json_decode($resultJSON);
    $csrfToken=$resultJSON->csrf;
    setcookie("jtools_session",$sessionCookie);
    setcookie("jtools_csrf",$csrfToken);
    if(curl_errno($ch)) {
        header("Location: /index.php?error3=Couldn't upload world (".curl_error($ch).")");
        exit();
    }   else{
        $resultJSON=json_decode($response);
        $username=$resultJSON->owner->username;
        $identifier=$resultJSON->identifier;
        if(empty($username) or empty($identifier))    {
            header("Location: /index.php?error3=Couldn't upload world");
            exit();
        }
        header("Location: /index.php?success=Successfully uploaded world under username '".$username."', with ID '".$identifier."'");
    }
}   else{
    header("Location: /index.php");
}
?>