<?php

require_once  '/usr/local/apache/security_files/connect.php';
require_once '../session.php';
require_once '../log/log.php';

global $db;


$sort = '';
if(isset($_GET['sort'])){
    $sort = $_GET['sort'];
}else{
    $sort = 'daily';
}


switch ($sort){
    case 'daily': //일별 사용자 통계


        $today=date("Y-m-d");//이걸 변경해주면 됨

        $total='';

        //시간대별로 방문자 수를 분류해서, array에 넣는다
        //원래 '방문자' 수를 계산해야됨!!! 지금은 '방문' 수를 통계낸거(똑같은 ip = 나 1명이 100번 방문)
        $dataPoints = array();
        for($i = 0; $i < 24; $i++){

            if($i<10){
                $sql_num_visitor = "SELECT count(*) FROM novelProject_accessLog WHERE datetime BETWEEN '".$today." 0".$i.":00:00' and '".$today." 0".$i.":59:59'";
                $result_num_visitor = mysqli_query($db, $sql_num_visitor);
            }else{
                $sql_num_visitor = "SELECT count(*) FROM novelProject_accessLog WHERE datetime BETWEEN '".$today." ".$i.":00:00' and '".$today." ".$i.":59:59'";
                $result_num_visitor = mysqli_query($db, $sql_num_visitor);
            }

            $row_visitor = mysqli_fetch_row($result_num_visitor);
            $y = $row_visitor[0];
            push_log2('num='.$y);


            $total+=$y;

            //dataPoints라는 배열에 array라는 값을 추가한다 - 다차원 배열
            //배열 구조: [[1,40], [2,50], [3,14], ... , [23,81]]
            array_push($dataPoints, array("x" => $i, "y" => $y));
        }


        break;
    case 'weekly': //주간 사용자 통계

        $today=date("Y-m-d");

        $total='';

        //한 주에서 오늘요일을 구함 (1~7)
        $today_dayOfWeek = date("N");

        $Y = date("Y");
        $m = date("m");
        $d = date("d");


        //이 주의 월요일을 구함
        $this_week_monday = date("Y-m-d", strtotime($Y."-".$m."-".$d." -".($today_dayOfWeek-1)." day"));
//            $this_week_end = date("Y-m-d", strtotime($this_week_start." +6 day"));

        //일별로 방문자 수를 분류해서, array에 넣는다
        //원래 '방문자' 수를 계산해야됨!!! 지금은 '방문' 수를 통계낸거(똑같은 ip = 나 1명이 100번 방문)
        $dataPoints = array();
//            $day_start = date("Y-m-d", strtotime("-7 days", strtotime($today)));
        $day_plus = $this_week_monday;
        for($i = 1; $i<=7 ; $i++){

            $sql_num_visitor = "SELECT count(*) FROM novelProject_accessLog WHERE datetime BETWEEN '".$day_plus." 00:00:00' and '".$day_plus." 23:59:59'";
            $result_num_visitor = mysqli_query($db, $sql_num_visitor);

            $row_visitor = mysqli_fetch_row($result_num_visitor);
            $y = $row_visitor[0];
            push_log2($day_plus.'num='.$y);

            $total+=$y;


            $dayOfWeek='';
            switch($i){
                case 1:
                    $dayOfWeek='Mon';
                    break;
                case 2:
                    $dayOfWeek='Tue';
                    break;
                case 3:
                    $dayOfWeek='Wed';
                    break;
                case 4:
                    $dayOfWeek='Thu';
                    break;
                case 5:
                    $dayOfWeek='Fri';
                    break;
                case 6:
                    $dayOfWeek='Sat';
                    break;
                case 7:
                    $dayOfWeek='Sun';
                    break;
            }

            //dataPoints라는 배열에 array라는 값을 추가한다 - 다차원 배열
            //배열 구조: [[1,40], [2,50], [3,14], ... , [23,81]]
            array_push($dataPoints, array("label" => $dayOfWeek, "y" => $y));

            //일요일 + 1일 = 다음주 월요일이 되므로, $i=7 일때는 아래 연산을 하지 않는다
            if($i<7){
                $day_plus = date("Y-m-d", strtotime("+1 days", strtotime($day_plus)));
            }
        }

        break;
    case 'monthly': //월간 사용자 통계

        $year=date("Y");
        $month=date("m");
        $this_month = date("Y-m");

        $today=date("Y-m-d");

        $start = date("Y-m-d", mktime(0, 0, 0, $month , 1, $year)); //이번 달의 시작일
        $number_of_days_this_month = date("t", strtotime($today));//해당 달의 일 수를 구한다

        $total='';

        //일별로 방문자 수를 분류해서, array에 넣는다
        //원래 '방문자' 수를 계산해야됨!!! 지금은 '방문' 수를 통계낸거(똑같은 ip = 나 1명이 100번 방문)
        $dataPoints = array();
//            $day_start = date("Y-m-d", strtotime("-".$number_of_days_this_month." day", strtotime($this_month)));
        push_log2('day_start='.$start);

        $day_plus = $start;
        for($i = 1; $i<=$number_of_days_this_month ; $i++){

            $sql_num_visitor = "SELECT count(*) FROM novelProject_accessLog WHERE datetime BETWEEN '".$day_plus." 00:00:00' and '".$day_plus." 23:59:59'";
            $result_num_visitor = mysqli_query($db, $sql_num_visitor);

            $row_visitor = mysqli_fetch_row($result_num_visitor);
            $y = $row_visitor[0];
            push_log2($day_plus.'num='.$y);

            $total+=$y;
            $day_plus = date("Y-m-d", strtotime("+1 days", strtotime($day_plus)));


            //dataPoints라는 배열에 array라는 값을 추가한다 - 다차원 배열
            //배열 구조: [[1,40], [2,50], [3,14], ... , [23,81]]
            array_push($dataPoints, array("x" => $i, "y" => $y));
        }

        break;
}