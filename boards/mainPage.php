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
            <div class="col-4" style="font-size: 30px; font-family: Times New Roman;">
                <a class="blog-header-logo text-dark" href="../index.php">ReadMe</a> | Fiction
            </div>

            <form class="form-inline">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <a class="text-muted" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-3"><circle cx="10.5" cy="10.5" r="7.5"></circle><line x1="21" y1="21" x2="15.8" y2="15.8"></line></svg>
                </a>
            </form>
            <div>
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Write
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="page_CreateNewStory.php">Create a New Story</a>
                        <a class="dropdown-item" href="#">My Stories</a>
                    </div>
                </div>
                <button class="btn btn-outline-secondary my-2 my-sm-0" onclick="location.href='login/login.php'" style="margin-right: 20px">Sign-in</button>
            </div>
        </div>
    </header>

</div>

<main role="main" class="container">

    <div class="jumbotron p-3 text-white rounded bg-dark" style="margin-top: 20px; margin-bottom: 30px;">
        <p>Refine by tag</p>
        <?php

        for($i=0; $i<30; $i++){
            echo '  <button class="btn btn-outline-success" style="margin:10px">thriller</button>';
        }

        ?>
    </div>

    <div class="row">
        <div class="col-md-8 blog-main" id="hot_post_list">
            <div style="margin-bottom: 50px">
                <div style="float:left; width: 80%;">1.2k stories</div>
                <div class="btn-group" style="float:left; width:20%">
                    <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Sort by: Rating
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">Rating</a>
                        <a class="dropdown-item" href="#">New</a>
                    </div>
                </div>
            </div>

            <?php

            for($i=0; $i<10; $i++){
                echo
                '<div class="hot_post">
                        <div class="card flex-md-row mb-4 box-shadow h-md-250">
                            <img src="../images/1.jpg" style="border-radius: 0 3px 3px 0; width:150px; height:180px; margin:10px" alt="Card image cap"/>
                            <div class="card-body d-flex flex-column align-items-start">
                                <strong class="d-inline-block mb-2 text-primary">World</strong>
                                <h3 class="mb-0">
                                    <a class="text-dark" href="#">Featured post</a>
                                </h3>
                                <div class="mb-1 text-muted">Nov 12</div>
                                <p class="card-text mb-auto">This is a wider card with supporting text below as a natural lead-in to additional content.</p>
                                <a href="#">Continue reading</a>
                            </div>
             
                        </div>
                    </div>';
            }
            ?>

            <nav aria-label="Page navigation example" style="margin-top: 50px; margin-bottom: 100px;">
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

        </div><!-- /.blog-main -->


        <aside class="col-md-4 blog-sidebar">

            <div style="margin-top: 50px; margin-bottom:30px">
                <div>
                    <div><h4 class="font-italic">Daily Best</h4></div>
                </div>
                <ul class="list-group mb-3">
                    <?php
                    for($i=0; $i<10; $i++) {
                        echo '
                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                    <div>
                                        <h6 class="my-0">Harry Potter - '.$i.'화</h6>
                                        <small class="text-muted">J.K. Rowling</small>
                                    </div>
                                    <span class="text-muted">Fantasy</span>
                                </li>';
                    }
                    ?>

                </ul>
            </div>

        </aside>

    </div><!-- /.row -->

    <!--        스크롤 맨 위로 올리는 버튼-->
    <div class="gotop" style="position: fixed; bottom: 50px; right: 50px">
        <a href class="btn btn-outline-info my-2 my-sm-0">Top</a>
    </div>
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