<?php
//    function setLog($text){
//        $log_txt = $text;
//        $log_file = fopen("/log.txt", "a");
//        fwrite($log_file, $log_txt."\r\n");
//        fclose($log_file);
//    }

date_default_timezone_set('Asia/Seoul');

function push_log($log_str , $line){

    global $log_filename;

    $now        = getdate();
    $today      = $now['year']."/".$now['mon']."/".$now['mday'];
    $now_time   = $now['hours'].":".$now['minutes'].":".$now['seconds'];
    $now        = $today." ".$now_time;
    $filep = fopen("/usr/local/apache/htdocs/log/log.txt", "a");
    if(!$filep) {
        die("can't open log file : ". $log_filename);
    }
    fputs($filep, "{$now} : ({$line}) : {$log_str}\n\r");
    fclose($filep);
}

?>