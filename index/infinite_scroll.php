<?php
require_once '../functions.php';

    if(isset($_POST['scroll'])){

        $new_page ='';

        for($i=0; $i<5; $i++) {

            $randomNumber = generateRandomInt(25);
            $img_src = $randomNumber.'.jpg';

            $new_page .= '<div class="list_item" onclick="location.href=\'boards/read_post.php?board=fiction&ep_id=100\'" style="margin-bottom: 20px;">
                        <div class="card flex-md-row box-shadow h-md-250">
                            <img src="images/bookCover_dummy/'.$img_src.'" style="border-radius: 0 3px 3px 0; width:130px; height:190px; margin:10px" alt="Card image cap"/>
                            <div class="card-body d-flex flex-column align-items-start">
                                <strong class="d-inline-block mb-2 text-primary">Fiction - comedy</strong>
                                <h5 class="mb-0">
                                    <a class="text-dark">How I met your mother : episode 32</a>
                                </h5>
                                <div class="mb-1 text-muted">by jane doe</div>
                                <p class="card-text mb-auto">description</p>
                                <div style="margin-top: 10px; width:100%;">
                                    <div style="float:left; width:80%">30000 views * 1000 likes * 500 comments</div>
                                    <div class="text-muted" style="float:left; width:20%">Today 18:10:05</div>
                                </div>
                            </div>
             
                        </div>
                     </div>';

        }
        echo $new_page;// = ajax response. script.js 에서 댓글창으로 append 된다

        exit();

    }

?>