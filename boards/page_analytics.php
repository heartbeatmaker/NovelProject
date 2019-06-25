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

            $today=date("Y-m-d");//이걸 변경해주면 됨

            $total='';

            //일별로 방문자 수를 분류해서, array에 넣는다
            //원래 '방문자' 수를 계산해야됨!!! 지금은 '방문' 수를 통계낸거(똑같은 ip = 나 1명이 100번 방문)
            $dataPoints = array();
            $day_plus =$today;
            for($i = 1; $i<=7 ; $i++){

                $sql_num_visitor = "SELECT count(*) FROM novelProject_accessLog WHERE datetime BETWEEN '".$day_plus." 00:00:00' and '".$day_plus." 23:59:59'";
                $result_num_visitor = mysqli_query($db, $sql_num_visitor);

                $row_visitor = mysqli_fetch_row($result_num_visitor);
                $y = $row_visitor[0];
                push_log2($day_plus.'num='.$y);

                $total+=$y;
                $day_plus = date("Y-m-d", strtotime("+1 day", strtotime($day_plus)));


                //dataPoints라는 배열에 array라는 값을 추가한다 - 다차원 배열
                //배열 구조: [[1,40], [2,50], [3,14], ... , [23,81]]
                array_push($dataPoints, array("x" => $i, "y" => $y));
            }


            break;
        case 'monthly': //월간 사용자 통계

            $this_month = date("Y-m"); //이번 달을 구한다

            $number_of_days_this_month = date("t");//이번 달의 일 수를 구한다

            $today=date("Y-m-d");

            $total='';

            //일별로 방문자 수를 분류해서, array에 넣는다
            //원래 '방문자' 수를 계산해야됨!!! 지금은 '방문' 수를 통계낸거(똑같은 ip = 나 1명이 100번 방문)
            $dataPoints = array();
            $day_plus =$today;
            for($i = 1; $i<=$number_of_days_this_month ; $i++){

                $sql_num_visitor = "SELECT count(*) FROM novelProject_accessLog WHERE datetime BETWEEN '".$day_plus." 00:00:00' and '".$day_plus." 23:59:59'";
                $result_num_visitor = mysqli_query($db, $sql_num_visitor);

                $row_visitor = mysqli_fetch_row($result_num_visitor);
                $y = $row_visitor[0];
                push_log2($day_plus.'num='.$y);

                $total+=$y;
                $day_plus = date("Y-m-d", strtotime("+1 day", strtotime($day_plus)));


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

    <title>Dashboard Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <!-- Custom styles for this template -->
    <link href="../css/dashboard.css" rel="stylesheet">
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
                        <a class="nav-link active" href="#">
                            <span data-feather="home"></span>
                            Dashboard <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="file"></span>
                            Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="shopping-cart"></span>
                            Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="users"></span>
                            Customers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="bar-chart-2"></span>
                            Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="layers"></span>
                            Integrations
                        </a>
                    </li>
                </ul>

                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Saved reports</span>
                    <a class="d-flex align-items-center text-muted" href="#">
                        <span data-feather="plus-circle"></span>
                    </a>
                </h6>
                <ul class="nav flex-column mb-2">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="file-text"></span>
                            Current month
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="file-text"></span>
                            Last quarter
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="file-text"></span>
                            Social engagement
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span data-feather="file-text"></span>
                            Year-end sale
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">

            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2" style="text-transform: capitalize"><?php echo $sort?> Visitor Statistics</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <button class="btn btn-sm btn-outline-secondary" onclick="location.href='page_analytics.php?sort=daily'">Daily</button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="location.href='page_analytics.php?sort=weekly'">Weekly</button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="location.href='page_analytics.php?sort=monthly'">Monthly</button>
                    </div>
                    <div class='input-group date' id='datetimepicker1'>
                        <input type='text' class="form-control" />
                        <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div id="chartContainer" style="height: 370px; width: 100%;"></div>

        </main>
    </div>
</div>



<script type="text/javascript">
    $(function () {
        $('#datetimepicker1').datetimepicker();
    });
</script>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"><\/script>')</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

<!-- Icons -->
<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
<script>
    feather.replace()
</script>

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
                        echo $today.' : '.$total.' Visitors';
                    }else if($sort=='weekly'){
                        echo $today.' ~ '.$day_plus.' : '.$total.' Visitors';
                    }else if($sort=='monthly'){
                        echo $this_month.' : '.$total.' Visitors';
                    }
                    ?>"
            },
            data: [{
                type: "area",
                dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();

    }
</script>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>