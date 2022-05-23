<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
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
    <div>
        <a href="index.php">Home</a>
        <a href="index.php?page=dic.php">Click to Directory Traversal</a>
    </div>
    <?php
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
        $page = str_replace(array("http://", "https://"), "", $page);
        $page = str_replace(array("../", "..\""), "", $page);
        include($page);
    }
    ?>
</body>

</html>