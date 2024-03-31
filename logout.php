<?php
setcookie("jtools_session","",time()-1000);
setcookie("jtools_csrf","",time()-1000);
setcookie("jtools_refresh_session","",time()-1000);
header("Location: /index.php");
?>