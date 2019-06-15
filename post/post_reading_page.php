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

    <title>ReadMe</title>
</head>
<body>

<div class="container" >
    <header class="blog-header">
        <div class="row flex-nowrap justify-content-between align-items-center">
            <div class="col-4">
                <a class="blog-header-logo text-dark" style="font-size: 30px; font-family: Times New Roman;" href="#">ReadMe</a>
            </div>

            <form class="form-inline">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <a class="text-muted" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-3"><circle cx="10.5" cy="10.5" r="7.5"></circle><line x1="21" y1="21" x2="15.8" y2="15.8"></line></svg>
                </a>
            </form>
            <button class="btn btn-outline-info my-2 my-sm-0" onclick="location.href='login/login.php'" style="margin-right: 20px">Sign-in</button>
        </div>
    </header>

    <div class="nav-scroller py-1 mb-2">
        <nav class="nav d-flex justify-content-between bg-light">
            <a class="p-2 text-muted" style="margin-left: 80px;" href="#">Fandom</a>
            <a class="p-2 text-muted" href="#">Fiction</a>
            <a class="p-2 text-muted" href="#">Non-fiction</a>
            <a class="p-2 text-muted" href="#">Community</a>
            <a class="p-2 text-muted" href="#">Hot 100</a>
            <a class="p-2 text-muted" style="margin-right: 80px;" href="#">About</a>
        </nav>
    </div>

</div>

<main role="main" class="container">
    <div class="row">
        <div class="col-md-8 blog-main" id="hot_post_list">







        </div><!-- /.blog-main -->

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