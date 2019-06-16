<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

    <!--    dataTables-->
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">

    <!--    stylesheets-->
    <link href="../css/write/form-validation.css" rel="stylesheet">

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" type="text/javascript"/>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <title>ReadMe | Fiction</title>
</head>
<body>


<div class="container">
    <header class="blog-header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
            <div class="col-8" style="font-size: 30px; font-family: Times New Roman;">
                <a class="blog-header-logo text-dark" href="../index.php">ReadMe</a> | My Stories
</div>
<!--            <div>-->
<!--                <button class="btn btn-outline-secondary my-2 my-sm-0" onclick="location.href='mainPage.php'">Cancel</button>-->
<!--            </div>-->
        </div>
    </header>

</div>

<main role="main" class="container" style="width:80% ;margin-top: 50px; margin-bottom: 100px">

    <div class="row">

        <div class="col-md-10" style="margin:0px auto">
            <div style="width:100%; text-align: right">
                <button class="btn btn-info" style="margin-bottom: 30px; ">+ New Story</button>
            </div>
           <?php

            for($i=0; $i<10; $i++){
                echo
                    '<div>
                        <div class="card flex-md-row mb-4 box-shadow h-md-250">
                            
                            <div style="width:25%; float:left">
                                <img src="../images/1.jpg" style="border-radius: 0 3px 3px 0; width:130px; height:180px; margin: 20px 50px 20px" alt="Card image cap"/>
                                
                            </div>
                            
                            <div style="width:55%; float:left">
                                <div class="card-body d-flex flex-column align-items-start">
                                    <h3 class="mb-0">
                                        <a class="text-dark" href="#">Featured post</a>
                                    </h3>
                                    <div class="mb-1 text-muted" style="margin-top: 10px">2019.01.21 ~ 2019.06.16</div>
                                    <div style="margin-top: 10px">2 Part Stories</div>
                                    <div style="margin-top: 10px" class="font-italic; font-weight-bold">Completed</div>
                                    <div style="margin-top: 10px">2000 likes | 100 comments</div>
                                </div>
                            </div>
                            
                            <div style="width:30%; float:left">
                                <button class="btn btn-outline-success" style="margin-top: 30px; padding-left: 20px; padding-right: 30px">+ New Part</button>
                            </div>
                            
                        </div>   
                    </div>';
            }
            ?>

        </div>

        <nav aria-label="Page navigation example" style="margin:0px auto; margin-top: 100px">
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#">First</a></li>
                <li class="page-item">. . .</li>
                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">4</a></li>
                <li class="page-item"><a class="page-link" href="#">5</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                <li class="page-item">. . .</li>
                <li class="page-item"><a class="page-link" href="#">Last</a></li>
            </ul>
        </nav>


    </div><!-- /.row -->

</main><!-- /.container -->

<!--    <footer class="blog-footer">-->
<!--        <p>2019 by <a href="https://twitter.com/mdo">@mdo</a>.</p>-->
<!--        <p>-->
<!--            <a href="#">Back to top</a>-->
<!--        </p>-->
<!--    </footer>-->

</body>
<script>
$(document).ready(function () {

    //스크롤 맨 위로
    var speed = 100; // 스크롤속도
    $(".gotop").css("cursor", "pointer").click(function()
    {
        $('body, html').animate({scrollTop:0}, speed);
        });

});
</script>

</html>