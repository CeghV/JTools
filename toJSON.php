<?php
if(is_uploaded_file($_FILES["blob"]["tmp_name"])) {
    $blob=file_get_contents($_FILES["blob"]["tmp_name"]);
    $data=gzuncompress($blob);
    header("Content-Disposition: attachment; filename=\"jtools-world.json\"; Content-Type: application/json; charset=utf-8");
    echo($data);
    exit();
}   else{
    header("Location: /index.php");
}
?>