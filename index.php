<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jamango! Tools</title>
</head>
<body>
    <?php if(isset($_COOKIE["jtools_session"]) and isset($_COOKIE["jtools_csrf"]) and isset($_COOKIE["jtools_refresh_session"])){?>
    <h1>Jamango! Tools</h1>
    <h3>World blob to JSON</h3>
    <form enctype="multipart/form-data" action="toJSON.php" method="POST">
        <label for="blob">World blob file:</label>
        <input type="file" name="blob" id="blob"><br><br>
        <input type="submit" value="Convert World" name="submit"/>
    </form>
    <h3>JSON to world blob</h3>
    <form enctype="multipart/form-data" action="toBlob.php" method="POST">
        <label for="json">JSON file:</label>
        <input type="file" name="json" id="json"><br><br>
        <input type="submit" value="Convert World" name="submit"/>
    </form>
    <h3>Download world</h3>
    <h4 style="color: darkred;"><?php echo($_GET["error1"])?></h4>
    <form enctype="multipart/form-data" action="exportWorld.php" method="POST">
        <label for="id">World ID:</label>
        <input type="text" name="id" id="id" maxlength="5"><br><br>
        <label for="type">Download as:</label>
        <select name="type" id="type">
            <option value="blob">Blob (recommended)</option>
            <option value="json">JSON</option>
        </select><br><br>
        <input type="submit" value="Download World" name="submit">
    </form>
    <h3>Download world inventory</h3>
    <h4 style="color: darkred;"><?php echo($_GET["error2"])?></h4>
    <form enctype="multipart/form-data" action="exportInventory.php" method="POST">
        <label for="id">World ID:</label>
        <input type="text" name="id" id="id" maxlength="5"><br><br>
        <input type="submit" value="Download Inventory" name="submit">
    </form>
    <h3>Upload world</h3>
    <h4 style="color: darkred;"><?php echo($_GET["error3"])?></h4>
    <h4 style="color: darkgreen;"><?php echo($_GET["success"])?></h4>
    <form enctype="multipart/form-data" action="importWorld.php" method="POST">
        <label for="name">World name:</label>
        <input type="text" name="name" id="name"><br><br>
        <label for="world">World file (blob or JSON):</label>
        <input type="file" name="world" id="world"><br><br>
        <label for="inventory">Inventory file (optional/recommended):</label>
        <input type="file" name="inventory" id="inventory"><br><br>
        <label for="generator">Generator type:</label>
        <select name="generator" id="generator">
            <option value="blank">Blank World</option>
            <option value="mvr">Template/Terrain</option>
        </select><br><br>
        <label for="id">World ID (leave blank for new world):</label>
        <input type="text" name="id" id="id" maxlength="5"><br><br>
        <input type="submit" value="Upload World" name="submit">
    </form>
    <h3>Account</h3>
    <a href="refresh.php"><button>Refresh credentials</button></a><br><br>
    <a href="logout.php"><button>Log out</button></a>
    <?php } else{?>
    <h1>Please log in with your Jamango! account</h1>
    <h4 style="color: darkred;"><?php echo($_GET["error"])?></h4>
    <form enctype="multipart/form-data" action="login.php" method="POST">
        <label for="login-email">Email:</label>
        <input type="email" name="login-email" id="login-email"><br><br>
        <label for="login-password">Password:</label>
        <input type="password" name="login-password" id="login-password"><br><br>
        <label for="submit">Enter info:</label>
        <input type="submit" value="Log in" name="submit">
    </form>
    <?php }?>
</body>
</html>