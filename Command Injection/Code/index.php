<?php
if (isset($_POST['submit'])) {
    $target = $_REQUEST['ip'];
    if (stristr(php_uname('s'), 'Windows NT')) {
        $cmd = shell_exec('ping ' . $target);
        //$cmd = shell_exec(escapeshellcmd("ping " . escapeshellarg($target)));
    } else {
        $cmd = shell_exec('ping -c 3 ' . $target);
    }
    echo "<pre>" . $cmd . "</pre>";
}
?>
<html>

<head>
    <style>
        body {
            text-align: center;
        }
    </style>
</head>
<form action="index.php" method="POST">
    <p>Nhập IP:</p>
    <input type="text" name="ip">
    <input value="Submit" type="submit" name="submit">
</form>

</html>