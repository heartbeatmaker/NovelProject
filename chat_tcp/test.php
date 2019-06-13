<?php
ob_start();

for ($i = 0; $i < 10; $i++) {
    echo $i;
    echo "<br>";
    echo str_pad('', 4096);

    ob_flush();
    flush();
    sleep(1);
}

ob_end_flush();
?>