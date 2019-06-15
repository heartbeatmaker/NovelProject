<?php

require_once '../session.php';

$sql_info = "INSERT INTO blog_post(title)VALUES('test')";
mysqli_query($db, $sql_info);

?>