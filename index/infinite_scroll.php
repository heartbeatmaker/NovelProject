<?php
    if(isset($_POST['scroll'])){

        $new_page ='';

        for($i=0; $i<5; $i++) {
            $new_page .= '<div class="hot_post">
                        <div class="card flex-md-row mb-4 box-shadow h-md-250">
                            <div class="card-body d-flex flex-column align-items-start">
                                <strong class="d-inline-block mb-2 text-primary">World</strong>
                                <h3 class="mb-0">
                                    <a class="text-dark" href="#">Featured post</a>
                                </h3>
                                <div class="mb-1 text-muted">Nov 12</div>
                                <p class="card-text mb-auto">This is a wider card with supporting text below as a natural lead-in to additional content.</p>
                                <a href="#">Continue reading</a>
                            </div>
                            <img src="images/1.jpg" style="border-radius: 0 3px 3px 0; width:180px; height:180px; margin:10px" alt="Card image cap"/>
                        </div>
                    </div>
                 ';
        }
        echo $new_page;// = ajax response. script.js 에서 댓글창으로 append 된다

        exit();

    }

?>