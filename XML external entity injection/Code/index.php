<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Trang chủ
    </title>
    <style>
        body {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Welcome to my channel</h1>
    <?php
    $xmlfile = file_get_contents('xee_error.xml');
    $dom = new DOMDocument();
    $dom->loadXML($xmlfile, LIBXML_NOENT | LIBXML_DTDLOAD);
    $creds = simplexml_import_dom($dom);
    $user = $creds->user;
    $pass = $creds->pass;
    echo "My name is $user"; ?>
</body>

</html>