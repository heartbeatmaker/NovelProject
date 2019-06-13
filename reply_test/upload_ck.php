<?php

require_once '../connect.php';

$sql_info = "INSERT INTO blog_post(title)VALUES('test')";
mysqli_query($db, $sql_info);

?>