<?php

    require_once  '/usr/local/apache/security_files/connect.php';
    require_once '../session.php';
    require_once '../log/log.php';

    global $db;
    accessLog();


$sort = '';
if(isset($_GET['sort'])){
    $sort = $_GET['sort'];
}else{
    $sort = 'daily';
}

$selected_date = '';
if(isset($_GET['date'])){
    $received_date = strtotime($_GET['date']); // d/m/Y 형식으로 날짜를 받아옴
    $selected_date = date('Y-m-d', $received_date);
}else{
    $selected_date = date("Y-m-d");
}


switch ($sort){
    case 'daily': //일별 사용자 통계


        $total='';

        //시간대별로 방문자 수를 분류해서, array에 넣는다
        //원래 '방문자' 수를 계산해야됨!!! 지금은 '방문' 수를 통계낸거(똑같은 ip = 나 1명이 100번 방문)
        $dataPoints = array();
        for($i = 0; $i < 24; $i++){

            if($i<10){
                $sql_num_visitor = "SELECT count(*) FROM novelProject_accessLog WHERE datetime BETWEEN '".$selected_date." 0".$i.":00:00' and '".$selected_date." 0".$i.":59:59'";
                $result_num_visitor = mysqli_query($db, $sql_num_visitor);
            }else{
                $sql_num_visitor = "SELECT count(*) FROM novelProject_accessLog WHERE datetime BETWEEN '".$selected_date." ".$i.":00:00' and '".$selected_date." ".$i.":59:59'";
                $result_num_visitor = mysqli_query($db, $sql_num_visitor);
            }

            $row_visitor = mysqli_fetch_row($result_num_visitor);
            $y = $row_visitor[0];


            $total+=$y;

            //dataPoints라는 배열에 array라는 값을 추가한다 - 다차원 배열
            //배열 구조: [[1,40], [2,50], [3,14], ... , [23,81]]
            array_push($dataPoints, array("x" => $i, "y" => $y));
        }


        break;
    case 'weekly': //주간 사용자 통계

        $total='';

        //한 주에서 해당 날짜의 요일을 구함 (1~7)
        $selectedDate_dayOfWeek = date("N", strtotime($selected_date));

        $Y = date("Y", strtotime($selected_date));
        $m = date("m", strtotime($selected_date));
        $d = date("d", strtotime($selected_date));



        //이 주의 월요일을 구함
        $this_week_monday = date("Y-m-d", strtotime($Y."-".$m."-".$d." -".($selectedDate_dayOfWeek-1)." day"));
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

        $year=date("Y", strtotime($selected_date));
        $month=date("m", strtotime($selected_date));
        $this_month = date("Y-m", strtotime($selected_date));


        $start = date("Y-m-d", mktime(0, 0, 0, $month , 1, $year)); //이번 달의 시작일
        $number_of_days_this_month = date("t", strtotime($selected_date));//해당 달의 일 수를 구한다

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


?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>ReadMe Analytics</title>

    <!-- Bootstrap core CSS -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

    <!-- Custom styles for this template -->
    <link href="../css/dashboard.css" rel="stylesheet">

<!--    date picker-->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $( function() {
            $( "#datepicker" ).datepicker({
                changeMonth: true,
                changeYear: true,
                onSelect: function(dateText, instance){ //사용자가 날짜를 선택했을 때 발생하는 이벤트

                    location.replace('page_analytics.php?sort=<?php echo $sort?>&date='+dateText);
                }

            });
        } );
    </script>

</head>

<body>
<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="../index.php">ReadMe Analytics</a>
    <input class="form-control form-control-dark w-100" type="text" aria-label="Search">
<!--    <ul class="navbar-nav px-3">-->
<!--        <li class="nav-item text-nowrap">-->
<!--            <a class="nav-link" href="#">Sign out</a>-->
<!--        </li>-->
<!--    </ul>-->
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="users"></span>
                            Visitors
                        </a>
                    </li>
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="#">-->
<!--                            <span data-feather="file"></span>-->
<!--                            Popular Pages-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="#">-->
<!--                            <span data-feather="search"></span>-->
<!--                            Search History-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="#">-->
<!--                            <span data-feather="bar-chart-2"></span>-->
<!--                            Action Analytics-->
<!--                        </a>-->
<!--                    </li>-->
                </ul>


            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">

            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h3" style="text-transform: capitalize"><?php echo $sort.' Visitor Statistics'?></h1>

                <p>Date: <input type="text" id="datepicker"></p>

                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <button class="btn btn-sm btn-outline-secondary <?php if($sort=='daily'){echo 'active';}?>" onclick="location.href='page_analytics.php?sort=daily&date=<?php echo $selected_date?>'">Daily</button>
                        <button class="btn btn-sm btn-outline-secondary <?php if($sort=='weekly'){echo 'active';}?>" onclick="location.href='page_analytics.php?sort=weekly&date=<?php echo $selected_date?>'">Weekly</button>
                        <button class="btn btn-sm btn-outline-secondary <?php if($sort=='monthly'){echo 'active';}?>" onclick="location.href='page_analytics.php?sort=monthly&date=<?php echo $selected_date?>'">Monthly</button>
                    </div>
                </div>

<!--                <div class='col-sm-3'>-->
<!--                    <div class="form-group">-->
<!--                        <div class='input-group date' id='datetimepicker1'>-->
<!--                            <input type='text' class="form-control" />-->
<!--                            <span class="input-group-addon">-->
<!--                        <span class="glyphicon glyphicon-calendar"></span>-->
<!--                    </span>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->

            </div>


            <div id="chartContainer" style="height: 370px; width: 100%;"></div>

        </main>
    </div>
</div>


<!-- Icons -->
<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
<script>
    feather.replace()
</script>
<!-- Icons -->


<!--<script type="text/javascript">-->
<!---->
<!--    $(document).ready(function () {-->
<!--        $('#datetimepicker1').datetimepicker();-->
<!--    });-->
<!--</script>-->


<!-- Graphs -->
<script>


    window.onload = function () {

        var chart = new CanvasJS.Chart("chartContainer", {
            theme: "light2", // "light1", "light2", "dark1", "dark2"
            animationEnabled: true,
            zoomEnabled: true,
            title: {
                text: "<?php
                    if($sort=='daily'){
                        echo $selected_date.' : '.$total.' Visits';
                    }else if($sort=='weekly'){
                        echo $this_week_monday.' ~ '.$day_plus.' : '.$total.' Visits';
                    }else if($sort=='monthly'){
                        echo $this_month.' : '.$total.' Visits';
                    }
                    ?>"
            },
            data: [{
                //area, bar, column 중 선택
                type: "column",
                dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
            }],
            axisY: {
                title: "Number of Visits",
                includeZero: false,
                // suffix: "",
                lineColor: "#369EAD"
            },
            axisX: {
                title: "<?php
                    if($sort=='daily'){
                        echo 'Hour of Day (24 hours)';
                    }else if($sort=='weekly'){
                        echo 'Day of Week';
                    }else if($sort=='monthly'){
                        echo 'Month of Year';
                    }
                    ?>",
                includeZero: false,
                // suffix: " m/s",
                lineColor: "#369EAD"
            }
        });
        chart.render();

    }
</script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<!-- Graphs -->

</body>
</html>
