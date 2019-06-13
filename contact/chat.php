<?php
require_once '../connect.php';


if(isset($_POST['signout_btn'])){

    $email = $_SESSION['email'];

    //해당 사용자의 db정보를 수정한다
    $query_deleteInfo = "UPDATE userinfo SET session_id=null WHERE email='$email'";
    mysqli_query($db, $query_deleteInfo);

    $_SESSION = array(); //세션 변수 전체를 초기화한다

    echo "<script>alert(\"로그아웃 되었습니다\");</script>";

}


?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style_index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

    <link rel="stylesheet" href="../law_wizard/css/dataTables.bootstrap.min.css">

    <title>SoftwareDeveloperYonJu</title>
</head>
<body>

<div class="wrapper">

    <div class="content_wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-secondary" style="height:60px">
            <!--                <a class="navbar-brand" href="#">Navbar</a>-->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item active" style="padding-right: 20px; padding-left: 20px">
                        <a class="nav-link" href="../home.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item" style="padding-right: 20px">
                        <a class="nav-link" href="../portfolio/portfolio.php">Portfolio</a>
                    </li>
                    <li class="nav-item" style="padding-right: 20px">
                        <a class="nav-link" href="../reply_test/main.php">Blog</a>
                    </li>
                    <li class="nav-item dropdown" style="padding-right: 25px">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Contact
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="contact.php">E-mail</a>
                            <a class="dropdown-item" href="#">Chat</a>
                        </div>
                    </li>

                    <?php
                    if(isset($_SESSION['user'])){
                        echo '<form action="" method="post"><button name="signout_btn" class="login_button">Sign-out</button></form>';
                    }else{
                        echo '<a class="nav-link" href="/login/login.php">Sign-in</a>';
                    }
                    ?>


                </ul>
            </div>
        </nav>

        <div class="main_content_wrapper" style="width:70%; margin:0px auto;">

<!--            관리자만 채팅방 목록을 볼 수 있다-->
            <?php if(isset($_SESSION['email']) && $_SESSION['email']=='admin@gmail.com'){

                echo '<h2>Chat Rooms</h2><br>
            <table class="table table-striped table-bordered table-hover" id="mydata">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Username</th>
                    <th>created_time</th>
                    <th>responded</th>
                </tr>
                </thead>
                <tbody>';
                $count=1;

                $sql = "SELECT*FROM chat_tcp WHERE isEmpty = 'N'"; //참여자가 나간 채팅방은 db에 기록만 남기고, 화면에는 표시하지 않는다
                $result = mysqli_query($db, $sql);

                while($row = mysqli_fetch_array($result)){?>
                <tr onclick="window.open('http://192.168.133.131:3000?name=yonju&room=<?php echo $row['room_id']?>','CHAT WITH ME'+<?php echo $count?>', 'width=430,height=500,location=no,status=no,scrollbars=yes')">
                    <td><?php echo $count++; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['created_time']; ?></td>
                    <td><?php echo $row['responded']; ?></td>
                </tr>
                <?php }
            }else{//비로그인 상태거나, 비 관리자계정으로 로그인 한 경우
                echo '<h2>Chat with Me</h2>
            <h6>Feel free to ask me any questions.</h6><br><br>

            <form class="chat_tcp-form" method="post" action="">
                <input id="chat_name" style="width:80%; float:left; padding:10px" type="text" name="name" placeholder="Your name" required="required">
                <button id="chat_button" type="submit" name="submit"
                        style="width:20%; padding:10px" type="text">START CHATTING</button>
                <br><br><br><br><br><br>
            </form>';
            }?>


                </tbody>

            </table>
            <br><br><br><br>
        </div>

    </div>

</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<script>
    $(document).ready(function(){

        $(document).on('click','#chat_button', function(){

            var username = $('#chat_name').val();

            window.open('http://192.168.133.131:3000?name='+username,'CHAT WITH ME','width=430,height=500,location=no,status=no,scrollbars=yes');
        });

    });
</script>

<script src="js/dataTables.bootstrap.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>

<script>
    $('#mydata').DataTable();
</script>



</body>
</html>