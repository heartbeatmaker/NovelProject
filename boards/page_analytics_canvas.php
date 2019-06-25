<?php

    require_once  '/usr/local/apache/security_files/connect.php';
    require_once '../session.php';
    require_once '../log/log.php';

    global $db;
    accessLog();


    $today=date("Y-m-d");//이걸 변경해주면 됨

    //시간대별로 방문자 수를 분류해서, array에 넣는다
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

        //dataPoints라는 배열에 array라는 값을 추가한다 - 다차원 배열
        //배열 구조: [[1,40], [2,50], [3,14], ... , [23,81]]
        array_push($dataPoints, array("x" => $i, "y" => $y));
    }

?>
<!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	animationEnabled: true,
	zoomEnabled: true,
	title: {
		text: "Visitor Statistics"
	},
	data: [{
		type: "area",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();

}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>