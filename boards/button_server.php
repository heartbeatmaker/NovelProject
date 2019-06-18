<?php
//read_post.php 에서 사용자가 좋아요 or 북마크 버튼을 눌렀을 때, db 작업 처리하는 파일

    require_once  '/usr/local/apache/security_files/connect.php';
    require_once '../session.php';
    require_once '../log/log.php';

    global $db;

    if(isset($_POST['button'])){

        $result='';

        if(isset($_SESSION['user'])){ //로그인 했는지 확인

            $btn_identity = $_POST['identity'];
            $episode_db_id = $_POST['episode_db_id'];
            $currentUser_email = $_SESSION['email'];

            if($btn_identity=='like'){

                //이 사용자가 이 글에 좋아요를 이미 누른 상태인지 확인
                $query_userInfo = "SELECT*FROM novelProject_userInfo WHERE email ='$currentUser_email'";
                $result_userInfo = mysqli_query($db, $query_userInfo) or die(mysqli_error($db));

                //사용자의 좋아요 목록을 가져온다
                $currentLikeHistory='';
                if(mysqli_num_rows($result_userInfo) == 1){
                    $row_userInfo = mysqli_fetch_array($result_userInfo);
                    $currentLikeHistory = $row_userInfo['likeHistory'];
                }
                push_log('사용자의 좋아요 목록을 가져온다');
                push_log('최초 currentLikeHistory='.$currentLikeHistory);


                if($currentLikeHistory == null){ //좋아요 목록이 비어있으면 -> 글db +1해주고 + 사용자 db에 글id 추가
                    push_log('좋아요 목록이 비어있음. 글db +1해주고 + 사용자 db에 글id 추가');

                    $query_episodeInfo = "UPDATE novelProject_episodeInfo SET numberOfLikes= numberOfLikes + 1 WHERE id='$episode_db_id'";
                    $result_episodeDB = mysqli_query($db, $query_episodeInfo);

                    $likeHistory_update = $episode_db_id;
                    $query_userInfo_update = "UPDATE novelProject_userInfo SET likeHistory = '$likeHistory_update' WHERE email='$currentUser_email'";
                    $result_episodeDB = mysqli_query($db, $query_userInfo_update);

                    $result='like';


                }else{ //좋아요 목록에 값이 있으면 -> 이 글에 좋아요를 이미 누른 상태인지 확인
                    push_log('좋아요 목록에 값이 있음. 이 글에 좋아요를 이미 누른 상태인지 확인한다');

                    $currentLikeHistory_split = explode(';', $currentLikeHistory);

                    $isAlreadyLiked=false;
                    $index='';
                    for($i=0; $i<count($currentLikeHistory_split); $i++){

                        push_log($i.'번째 좋아요 글의 db id: '.$currentLikeHistory_split[$i]);
                        if($currentLikeHistory_split[$i] == $episode_db_id){
                            $isAlreadyLiked=true;
                            push_log('이미 좋아요 목록에 있음');

                            //이 값 삭제를 위해 인덱스 저장
                            $index=$i;
                        }
                    }
                    push_log('좋아요 목록 확인 끝남. 결과는? isAlreadyLiked='.$isAlreadyLiked);

                    $likeHistory_update='';
                    //이 글에 좋아요를 이미 눌렀으면 -> 글db -1해주고 + 사용자 db에서 글id 삭제
                    if($isAlreadyLiked==true){
                        push_log('이 글에 좋아요를 이미 눌렀음 -> 글db -1해주고 + 사용자 db에서 글id 삭제');

                        $query_episodeInfo = "UPDATE novelProject_episodeInfo SET numberOfLikes= numberOfLikes - 1 WHERE id='$episode_db_id'";
                        $result_episodeDB = mysqli_query($db, $query_episodeInfo);

                        unset($currentLikeHistory_split[$index]);
                        $likeHistory_update = implode(';', $currentLikeHistory_split);
                        $query_userInfo_update = "UPDATE novelProject_userInfo SET likeHistory = '$likeHistory_update' WHERE email='$currentUser_email'";
                        $result_episodeDB = mysqli_query($db, $query_userInfo_update);

                        $result='unlike';

                    }else{ //처음 누른거면 -> 글db +1해주고 + 사용자 db에 글id 추가
                        push_log('이 글에 좋아요 처음 누름 -> 글db +1해주고 + 사용자 db에 글id 추가');

                        $query_episodeInfo = "UPDATE novelProject_episodeInfo SET numberOfLikes= numberOfLikes + 1 WHERE id='$episode_db_id'";
                        $result_episodeDB = mysqli_query($db, $query_episodeInfo);

                        $likeHistory_update = $currentLikeHistory.';'.$episode_db_id;
                        $query_userInfo_update = "UPDATE novelProject_userInfo SET likeHistory = '$likeHistory_update' WHERE email='$currentUser_email'";
                        $result_episodeDB = mysqli_query($db, $query_userInfo_update);

                        $result='like';
                    }

                }


                push_log('db에 update한 좋아요 목록 확인: likeHistory_update= '.$likeHistory_update);


            }else if($btn_identity=='bookmark'){

                //이 사용자가 이 글에 북마크를 이미 누른 상태인지 확인
                $query_userInfo = "SELECT*FROM novelProject_userInfo WHERE email ='$currentUser_email'";
                $result_userInfo = mysqli_query($db, $query_userInfo) or die(mysqli_error($db));

                //사용자의 북마크 목록을 가져온다
                $currentBookmark='';
                if(mysqli_num_rows($result_userInfo) == 1){
                    $row_userInfo = mysqli_fetch_array($result_userInfo);
                    $currentBookmark = $row_userInfo['bookmarkHistory'];
                }
                push_log('사용자의 북마크 목록을 가져온다');
                push_log('최초 currentBookmark='.$currentBookmark);


                if($currentBookmark == null){ //북마크 목록이 비어있으면 -> 글db +1해주고 + 사용자 db에 글id 추가
                    push_log('북마크 목록이 비어있음. 글db +1해주고 + 사용자 db에 글id 추가');

                    $query_episodeInfo = "UPDATE novelProject_episodeInfo SET bookmark= bookmark + 1 WHERE id='$episode_db_id'";
                    $result_episodeDB = mysqli_query($db, $query_episodeInfo);

                    $bookmark_update = $episode_db_id;
                    $query_userInfo_update = "UPDATE novelProject_userInfo SET bookmarkHistory = '$bookmark_update' WHERE email='$currentUser_email'";
                    $result_episodeDB = mysqli_query($db, $query_userInfo_update);

                    $result='bookmark';

                }else{ //북마크 목록에 값이 있으면 -> 이 글에 북마크를 이미 누른 상태인지 확인
                    push_log('북마크 목록에 값이 있음. 이 글에 북마크를 이미 누른 상태인지 확인한다');

                    $currentBookmark_split = explode(';', $currentBookmark);

                    $isAlreadyBookmarked=false;
                    $index='';
                    for($i=0; $i<count($currentBookmark_split); $i++){

                        push_log($i.'번째 북마크 글의 db id: '.$currentBookmark_split[$i]);
                        if($currentBookmark_split[$i] == $episode_db_id){
                            $isAlreadyBookmarked=true;
                            push_log('이미 북마크 목록에 있음');

                            //이 값 삭제를 위해 인덱스 저장
                            $index=$i;
                        }
                    }
                    push_log('북마크 목록 확인 끝남. 결과는? isAlreadyBookmarked='.$isAlreadyBookmarked);

                    $bookmark_update='';
                    //이 글에 북마크를 이미 눌렀으면 -> 글db -1해주고 + 사용자 db에서 글id 삭제
                    if($isAlreadyBookmarked==true){
                        push_log('이 글에 북마크를 이미 눌렀음 -> 글db -1해주고 + 사용자 db에서 글id 삭제');

                        $query_episodeInfo = "UPDATE novelProject_episodeInfo SET bookmark= bookmark - 1 WHERE id='$episode_db_id'";
                        $result_episodeDB = mysqli_query($db, $query_episodeInfo);

                        unset($currentBookmark_split[$index]);
                        $bookmark_update = implode(';', $currentBookmark_split);
                        $query_userInfo_update = "UPDATE novelProject_userInfo SET bookmarkHistory = '$bookmark_update' WHERE email='$currentUser_email'";
                        $result_episodeDB = mysqli_query($db, $query_userInfo_update);

                        $result='unbookmark';

                    }else{ //처음 누른거면 -> 글db +1해주고 + 사용자 db에 글id 추가
                        push_log('이 글에 북마크 처음 누름 -> 글db +1해주고 + 사용자 db에 글id 추가');

                        $query_episodeInfo = "UPDATE novelProject_episodeInfo SET bookmark= bookmark + 1 WHERE id='$episode_db_id'";
                        $result_episodeDB = mysqli_query($db, $query_episodeInfo);

                        $bookmark_update = $currentBookmark.';'.$episode_db_id;
                        $query_userInfo_update = "UPDATE novelProject_userInfo SET bookmarkHistory = '$bookmark_update' WHERE email='$currentUser_email'";
                        $result_episodeDB = mysqli_query($db, $query_userInfo_update);

                        $result='bookmark';

                    }

                }


                push_log('db에 update한 북마크 목록 확인: bookmark_update= '.$bookmark_update);

            }

        }else{//로그인 안 했으면 -> 좋아요/북마크 불가

            $result='login';
        }

        echo $result;
    }



?>
