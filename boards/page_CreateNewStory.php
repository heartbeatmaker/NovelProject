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
                <a class="blog-header-logo text-dark" href="../index.php">ReadMe</a> | Create a New Story
            </div>
            <div>
                <button class="btn btn-outline-secondary my-2 my-sm-0" onclick="location.href='mainPage.php'">Cancel</button>
            </div>
        </div>
    </header>

</div>

<main role="main" class="container" style="width:80% ;margin-top: 50px; margin-bottom: 100px">

    <div class="row">

<!--        북커버 삽입-->
        <aside class="col-md-3 blog-sidebar">
            <form style="margin-top: 50px; margin-bottom:30px; text-align: center; background-color: lightgrey; width: 200px; height: 280px;">
                <a href="#"><h5>Add a Cover</h5></a>
            </form>
        </aside><!-- /.blog-sidebar -->

<!--        공간띄우기용-->
        <aside class="col-md-1 blog-sidebar"></aside>

<!--        세부사항-->
        <div class="col-md-8 blog-main" id="hot_post_list">

            <h4 style="margin-bottom: 30px">Story Details</h4>
            <form class="needs-validation" novalidate>

                <div>
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" placeholder="" value="Untitled Story" required>
                    <div class="invalid-feedback">
                        Title is required.
                    </div>
                </div>


                <div class="mb-3" style="margin-top: 30px;">
                    <label for="description">Description</label>
                    <textarea type="text" class="form-control" id="description" rows="5" required></textarea>
                    <div class="invalid-feedback">
                        Please enter description of the story.
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label for="country">Genre</label>
                        <select class="custom-select d-block w-100" id="country" required>
                            <option value="">Choose...</option>
                            <option>Comedy</option>
                            <option>Comedy</option>
                            <option>Comedy</option>
                            <option>Comedy</option>
                            <option>Comedy</option>
                            <option>Comedy</option>
                            <option>Comedy</option>
                            <option>Comedy</option>
                            <option>Comedy</option>
                            <option>Comedy</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a valid country.
                        </div>
                    </div>

                </div>

                <hr class="mb-4">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="same-address">
                    <label class="custom-control-label" for="same-address">Anonymous</label>
                </div>

                <hr class="mb-4">
                <button class="btn btn-info btn-lg btn-block" type="submit">Done</button>
            </form>

        </div><!-- /.blog-main -->

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