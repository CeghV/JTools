<?php
if(is_uploaded_file($_FILES["json"]["tmp_name"])) {
    $json=file_get_contents($_FILES["json"]["tmp_name"]);
    $data=gzcompress($json);
    header("Content-Disposition: attachment; filename=\"jtools-blob.bin\"; Content-Type: application/octet-stream; charset=utf-8");
    echo($data);
    exit();
}   else{
    header("Location: /index.php");
}
?>