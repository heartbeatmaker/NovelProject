<?php

require_once  '/usr/local/apache/security_files/connect.php';
require_once 'log/log.php';
require_once 'functions.php';

global $db;



//$id = 1047;
//$author_name = '';
//
////db에 저장된 fiction 장르를 가져온다
//$board_name = 'fiction';
//$sql_tableName='';
//if($board_name=='fiction'){
//    $sql_tableName='novelProject_episodeInfo';
//}
//
//$genre ='';
//$sql_genre = "SELECT*FROM novelProject_boardInfo WHERE name='$board_name'";
//$result_genre = mysqli_query($db, $sql_genre);
//
////string으로 이어서 가져온 장르를 개별로 분할하여 화면에 출력한다
//if(mysqli_num_rows($result_genre)==1){
//    $row_genre = mysqli_fetch_array($result_genre);
//    $genre_string = $row_genre['category'];
//}
//$genre_split_array = explode(';', $genre_string);
//
//$max_num = count($genre_split_array)-1;
//
//for($i=0; $i<100; $i++){
//
//    $random_number =   $randomNum = mt_rand(0, $max_num);
//    $random_genre = $genre_split_array[$random_number];
//
//    $sql_author = "SELECT author_username FROM novelProject_storyInfo WHERE id='$id'";
//    $result_author = mysqli_query($db, $sql_author);
//    if(mysqli_num_rows($result_author) ==1){
//        $author_name = mysqli_fetch_array($result_author)['author_username'];
//    }
//    $author_email = explode(' ', $author_name)[0];
//    $author_email_lowercase = strtolower($author_email).'@gmail.com';
//
//
//    Generate a timestamp using mt_rand.
//    $timestamp = mt_rand(1, time());
//    Format that timestamp into a readable date string.
//    $randomDate = date("Y/m/d", $timestamp);
//    $randomDate = date('Y/m/d', strtotime( '-'.mt_rand(0,1800).' days'));
//
//
//    $sql_update = "UPDATE novelProject_storyInfo SET startDate ='$randomDate' WHERE id ='$id'";
//
//    $sql_storyInfo = "INSERT INTO novelProject_storyInfo(genre, author_email,
//startDate, lastUpdate, image)VALUES('$title','$description','$genre','$author_email'
//,'$author_username','$isCompleted','$date','$date', 0, '$image_file_name')";
//
//    $result = mysqli_query($db, $sql_update);
//
//    $id += 1;
//}



$content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent quis magna magna. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Maecenas auctor neque consectetur leo laoreet iaculis. Cras at auctor sapien, a auctor purus. Ut at libero sollicitudin, tempus turpis efficitur, tempor urna. Aenean a efficitur ipsum. Suspendisse tellus metus, eleifend nec eleifend non, euismod et velit. Maecenas id felis vitae orci venenatis vehicula in sed augue. Nulla facilisi. Nam non leo a ipsum rutrum dapibus vel sed odio. Integer eget hendrerit arcu, sit amet tincidunt mi. Praesent vestibulum odio ut diam ullamcorper congue. Pellentesque massa purus, rutrum vel nisl at, semper euismod nibh. Phasellus quis quam nisi. Integer tellus urna, laoreet ac purus sodales, dictum ornare orci. Curabitur mi nulla, pharetra non nibh ac, vestibulum suscipit sapien.
    
    Quisque pulvinar diam at felis vulputate, non gravida dui varius. Integer eu magna rutrum, bibendum tellus eu, pretium tellus. Vivamus eu velit et ligula sollicitudin fermentum. Suspendisse nec erat sapien. In hac habitasse platea dictumst. Morbi bibendum nibh purus, et accumsan diam scelerisque id. Fusce urna felis, tincidunt vel magna ut, aliquet ullamcorper justo. Nullam vel sodales nisl. Nam posuere ligula a metus euismod, eu aliquet sapien pulvinar. Quisque nec lacinia neque, sit amet condimentum nisl.
    
    Vivamus vehicula, dui vitae feugiat varius, lectus enim gravida justo, ac finibus tortor dolor in justo. In dui erat, fringilla ut augue eu, feugiat aliquam mauris. Suspendisse eu ultricies dui. Ut vestibulum tortor non odio pretium suscipit. Donec a mauris et tortor cursus lobortis. Pellentesque tempus enim enim, ut sollicitudin lectus feugiat et. Maecenas accumsan fringilla magna eleifend mollis. Praesent rutrum vulputate pellentesque. Mauris at ultricies elit, blandit ornare velit. Cras tincidunt vel libero id sollicitudin. Phasellus purus justo, eleifend vitae neque et, pharetra ultrices lorem. Praesent vitae lectus a eros iaculis varius quis non ipsum. Proin lectus nibh, blandit a neque nec, pretium molestie orci. Pellentesque tincidunt porttitor velit, id ornare quam suscipit eget.
    
    Ut vestibulum pretium neque nec varius. Maecenas sagittis risus facilisis, ullamcorper felis id, mollis nibh. Pellentesque id nisi et arcu consequat mollis. Nullam laoreet consequat gravida. Nulla lobortis nisi et lacus varius scelerisque. Morbi sit amet ornare ligula, id sodales ipsum. Praesent sit amet tristique tortor, et cursus turpis. Quisque nibh elit, ultricies ac ligula et, imperdiet convallis nunc. Cras ornare vehicula magna vel varius. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Pellentesque condimentum interdum orci sed maximus.
    
    Vestibulum pharetra magna volutpat nulla commodo faucibus. Nam scelerisque massa consectetur mi venenatis, consectetur luctus libero fringilla. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam nec enim dignissim, semper metus vitae, elementum nisi. Nullam dapibus tincidunt pulvinar. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer aliquet sollicitudin nisl sed faucibus. Praesent nunc enim, interdum sed varius quis, gravida non mauris. Vestibulum at risus eu purus tristique pretium. Morbi lacus enim, porta id ex vitae, finibus venenatis leo. Phasellus congue vel elit ut pretium. Nulla enim nulla, efficitur at purus sit amet, laoreet hendrerit tellus. Quisque id laoreet tellus.
    
    Integer gravida metus dui, sit amet elementum odio ultricies id. Sed tempor dui non sapien congue, at pretium nisi vehicula. Donec vitae dui sem. Nunc congue ut tortor et vestibulum. In hac habitasse platea dictumst. Integer vitae malesuada enim. Maecenas blandit eros ut aliquet suscipit.
    
    Proin vitae efficitur purus. Mauris id volutpat neque. Etiam id odio elit. Etiam tellus urna, sollicitudin et ipsum sed, congue accumsan erat. Morbi sed nunc quis enim fermentum venenatis. Proin in posuere nibh. Aenean eget metus mollis, pellentesque magna nec, vehicula nibh. Sed nisl libero, rutrum a iaculis id, tincidunt cursus sem. Maecenas semper dolor ac sollicitudin faucibus.
    
    Maecenas venenatis nibh id libero tempor, porttitor semper diam imperdiet. Nunc a erat bibendum, condimentum diam eget, feugiat enim. Quisque tempor est nec nunc consectetur consequat. Aenean id libero orci. Nulla scelerisque condimentum molestie. Donec ipsum metus, sagittis in sapien ac, maximus pharetra sapien. Duis tincidunt, quam at porttitor sodales, justo risus semper mauris, sed volutpat libero urna in odio. Aliquam turpis leo, placerat at risus at, suscipit blandit augue.
    
    Praesent dignissim, massa vitae placerat lobortis, mi elit hendrerit erat, vel semper velit nisl id felis. Etiam ac congue libero. Fusce viverra tortor nec felis mattis, egestas suscipit mi varius. Donec auctor metus sed diam ultricies lacinia. Curabitur ut ultricies odio. Aliquam tempor dui ac purus finibus, vitae vestibulum urna ultricies. Nullam viverra condimentum diam ut placerat. In hac habitasse platea dictumst. Etiam molestie arcu ut velit gravida, vitae sollicitudin lectus hendrerit. In id leo quis arcu suscipit fermentum. Quisque condimentum ipsum nisi, ut consequat velit fermentum in. In facilisis sagittis nisl, vel eleifend justo posuere ac. Mauris cursus ullamcorper aliquet. Ut sed scelerisque velit. Nam blandit massa ut eros vestibulum, id commodo lectus euismod.
    
    Suspendisse potenti. Pellentesque eget finibus felis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam libero enim, vestibulum quis sagittis ac, interdum at tellus. Pellentesque blandit leo at orci dignissim aliquam. Nulla porta nibh eros, in luctus orci eleifend non. Etiam dignissim metus eget luctus tempor. Nam elementum lorem nibh, non aliquet dui auctor ut. Quisque quis nulla elit. Suspendisse ornare sapien ante, eget venenatis leo vestibulum quis.
    
    Sed dapibus sollicitudin libero pulvinar consequat. In et odio nulla. Suspendisse sollicitudin magna quis lacinia blandit. Vivamus a mollis est. Ut pellentesque erat vitae nisl volutpat, blandit mollis tellus dignissim. Maecenas odio nunc, tincidunt in viverra non, condimentum vitae leo. Proin feugiat risus at fringilla ultricies. Nullam felis neque, dapibus eu porta at, accumsan eget mi. Praesent sollicitudin metus quis convallis tempor. Quisque ut purus gravida, maximus ante sed, pellentesque velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed sed sagittis quam. In laoreet accumsan malesuada. Suspendisse placerat risus in nisi tempus, in laoreet erat posuere. Vestibulum ac ligula luctus, gravida nunc eget, fringilla dui.
    
    Vivamus condimentum euismod efficitur. Nulla tempus pellentesque bibendum. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque consectetur interdum mi, a accumsan lectus. Aliquam vulputate ligula tellus, vel eleifend ipsum pharetra ac. Nullam ac suscipit quam. Pellentesque eu enim quis dolor pharetra accumsan. Phasellus at metus a nisl elementum pretium. Pellentesque non nibh et lorem elementum sagittis. Aliquam erat volutpat.
    
    Nunc pellentesque neque non dui mollis blandit. Interdum et malesuada fames ac ante ipsum primis in faucibus. Mauris blandit euismod nulla nec varius. Mauris non nisi mauris. Morbi consequat ut lacus tincidunt venenatis. Nunc scelerisque ligula ex, quis dapibus odio dictum eu. Donec turpis tortor, aliquet sed maximus sit amet, facilisis ut sapien. Nulla consectetur nisl nisl. Vestibulum eu auctor sem. Quisque sit amet diam metus. Quisque cursus nulla eget turpis cursus hendrerit. Sed tellus ligula, lobortis sit amet purus vitae, tempor rhoncus magna. Nunc eros justo, accumsan semper metus sed, ultrices vulputate tortor.
    
    Curabitur ultrices libero at mi tincidunt vulputate. Cras quis pulvinar sem. Proin dictum magna elit, ac tincidunt libero mattis quis. Nulla rhoncus eros vitae commodo egestas. Pellentesque porttitor nibh at sem iaculis porttitor. Pellentesque posuere quam sit amet bibendum pulvinar. Integer eros eros, tempus eget mi nec, ornare mattis nibh. Morbi molestie libero consectetur ipsum venenatis venenatis. Nunc gravida pharetra nibh, a bibendum nunc. Etiam bibendum mi non ex ultrices, ut auctor justo facilisis. Aliquam blandit eu tellus a varius.
    
    Nulla rhoncus, eros in accumsan iaculis, arcu felis vehicula metus, sit amet dictum sem tellus tristique libero. In vitae tristique nisl. Curabitur non mollis nisl. Donec feugiat dolor ante, vitae sagittis felis pellentesque ac. Suspendisse imperdiet tellus dui, ut bibendum ligula sollicitudin in. Nullam volutpat odio nulla. Suspendisse purus diam, convallis quis volutpat nec, imperdiet scelerisque est. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;
    
    Mauris iaculis dolor tellus. Duis sit amet lacus nec ipsum volutpat accumsan. Cras vel sapien dictum, ultricies risus a, tempor magna. Aliquam commodo purus non eros lacinia rhoncus. Fusce commodo ullamcorper lacinia. Morbi eu interdum augue. Proin porta sodales tellus, eget dapibus odio volutpat sed. Integer egestas diam at mi bibendum ullamcorper.
    
    Quisque ut nunc lacus. Ut erat elit, pulvinar in mi vel, congue pellentesque augue. Praesent laoreet neque non pharetra porta. Donec ultricies rhoncus augue, eget scelerisque arcu mattis eu. Sed ligula leo, dignissim dignissim augue a, aliquet vehicula orci. Ut nec lacus eget justo malesuada dictum. Aenean sed lectus eu nibh pharetra tristique. Quisque dapibus massa sem, quis molestie nunc tincidunt vel. Praesent iaculis blandit velit, quis ullamcorper risus interdum tincidunt. Quisque fringilla, leo ut imperdiet viverra, ligula tortor pharetra nunc, eu fermentum nisi ipsum id ex. Donec facilisis ligula massa, et lobortis orci hendrerit a. Cras interdum aliquet odio in pellentesque. Sed eros lacus, venenatis ac pulvinar sed, pretium eu mi. Curabitur nec lobortis lectus. Ut hendrerit dignissim blandit.
    
    Proin ac mattis nibh. Mauris id semper risus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Morbi at erat posuere, faucibus metus vitae, faucibus enim. Praesent aliquam magna ut dolor efficitur aliquet. Integer sodales semper neque. Pellentesque ut massa dignissim, finibus tellus sed, venenatis mauris. Integer et orci pretium, mollis tortor quis, lobortis leo. In vehicula vulputate leo sit amet tincidunt. Vestibulum ac placerat odio.
    
    Maecenas lorem neque, imperdiet faucibus volutpat nec, suscipit sed dolor. Curabitur quis velit vel turpis elementum vulputate. Integer a blandit mauris, ac cursus magna. Etiam non semper odio. Vivamus ornare commodo arcu vel varius. Donec quam purus, efficitur id sem id, commodo feugiat nulla. Pellentesque convallis velit sit amet arcu dictum pretium. Suspendisse potenti.
    
    Phasellus nibh leo, hendrerit auctor arcu in, finibus cursus sem. Nam maximus turpis in purus cursus, id consectetur velit rhoncus. Morbi sit amet velit ac lacus interdum tempus. Fusce ut metus vitae velit commodo mattis auctor ac eros. Integer quis erat quis eros consectetur lacinia. Aliquam at sem varius, lacinia nibh non, venenatis mi. Ut commodo, nunc auctor aliquet semper, sapien nunc luctus arcu, ut rhoncus tellus ligula vel velit. Integer sed blandit nisl. Sed in nulla eu mauris vehicula semper. Integer gravida tincidunt elementum. Pellentesque convallis tellus vel nulla congue condimentum. Maecenas rutrum, quam vel placerat vestibulum, neque nulla vestibulum orci, at imperdiet lacus ante ut tellus.
    
    In hac habitasse platea dictumst. Quisque rutrum erat sed justo venenatis, sit amet ullamcorper odio commodo. Donec et sem eget felis semper pellentesque eu ut velit. Sed erat arcu, laoreet eu odio dapibus, porta tempus turpis. Integer semper ante risus, nec vestibulum purus accumsan a. Praesent eget turpis turpis. Praesent fermentum dignissim arcu, eu accumsan libero eleifend quis. Aliquam tincidunt tempor ultrices. Nullam rutrum at ipsum porta laoreet. Aliquam eu imperdiet erat. Pellentesque nec tortor lectus.
    
    In sit amet auctor velit. Curabitur id augue eget ipsum ultrices vulputate. Donec vulputate ullamcorper neque eget imperdiet. Phasellus mollis fringilla neque, quis eleifend dolor sagittis volutpat. Morbi condimentum lacus ligula, eu ultrices mi iaculis ac. Maecenas placerat nulla non diam maximus, sit amet varius nulla facilisis. Nam scelerisque sapien ac erat condimentum, at finibus nibh tincidunt. In at quam ligula. Sed odio turpis, ultrices sit amet blandit ut, imperdiet a massa.
    
    Etiam mattis blandit porta. Nunc porta mollis dolor. Suspendisse tincidunt sapien orci, id maximus ex dictum nec. Sed scelerisque eleifend magna eu cursus. Morbi et nulla eu libero interdum suscipit eget gravida urna. In maximus dolor lacus, non aliquam felis bibendum eget. Aliquam tempus quis augue non dapibus. Phasellus luctus ut purus id sollicitudin. Morbi scelerisque vulputate nunc, ut tristique quam suscipit nec. In feugiat, elit et rutrum convallis, urna enim convallis turpis, vitae cursus libero mauris quis velit. Praesent eu massa metus. Praesent eget leo eu ipsum maximus accumsan. Nam cursus vehicula tempor. In laoreet vel magna non elementum.
    
    Sed lacus velit, convallis ut est at, finibus interdum odio. Nullam sed est lorem. Mauris vitae dapibus eros. Aenean vitae elit sollicitudin leo ullamcorper viverra. Sed eu lorem eget mauris rutrum vestibulum vel ac dolor. Vivamus et sapien viverra, luctus arcu vitae, rutrum odio. Etiam massa ante, tempor et feugiat nec, rhoncus a ante. In hac habitasse platea dictumst. Nam sollicitudin et urna quis pulvinar. Curabitur rhoncus quam eu turpis dapibus, id interdum arcu blandit. Morbi aliquam venenatis urna, sit amet placerat eros vulputate eu. Nam accumsan, tortor vel efficitur molestie, justo nisi efficitur purus, ac porttitor ante ex sit amet leo. Maecenas ullamcorper mauris non ex imperdiet, eget feugiat ex tempus. Ut mattis odio ac diam egestas hendrerit. Nulla lobortis sapien quis sapien blandit iaculis.
    
    Duis posuere erat ut risus tincidunt volutpat. Integer finibus accumsan nunc, eget sollicitudin mi elementum et. Curabitur in ullamcorper libero. Suspendisse potenti. Curabitur sollicitudin, lectus eu pretium accumsan, ex nulla mollis risus, at congue velit sem quis urna. Curabitur viverra ultricies turpis, non consectetur nisl tincidunt faucibus. Curabitur erat felis, vulputate et nunc eget, fermentum scelerisque ex. Nam eros mauris, mollis eu nisi ut, malesuada ultricies augue.
    
    Cras et orci sit amet elit tempor dictum sed at quam. Duis in enim sed nisi tempor aliquet a a orci. Nullam sapien erat, aliquam vulputate risus non, porta pretium felis. Aliquam sit amet enim eu dolor tincidunt malesuada. Ut posuere nisl at euismod posuere. Aliquam aliquam, nisl at elementum fermentum, risus ex porta dui, at pulvinar nunc elit ut lorem. Nam quis metus ac ipsum ornare ultricies. Suspendisse luctus euismod risus, ut ornare sapien mollis ut. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec nisl massa, dapibus vel turpis sed, faucibus consectetur mi.
    
    Quisque cursus vulputate arcu, sit amet congue neque. Mauris lorem velit, semper et condimentum nec, placerat quis ipsum. Praesent mattis arcu mauris, eu tincidunt diam tincidunt a. Sed quis pellentesque nisl. Cras pellentesque arcu urna, tincidunt rutrum libero fringilla et. Mauris vehicula sollicitudin nisi, posuere ultricies mauris faucibus in. Nulla est odio, rhoncus ac odio ut, porttitor viverra nunc. Vestibulum faucibus nibh sed erat egestas, ut malesuada nisl interdum. Aliquam consectetur nunc eros, ut iaculis elit mollis eu.
    
    Sed sagittis luctus gravida. Proin eget risus id est consectetur gravida. Morbi a sapien vel mauris molestie tempus. In venenatis erat sit amet malesuada aliquet. In augue magna, venenatis eu tortor ut, semper finibus erat. Donec consectetur, velit id lacinia sagittis, mauris risus accumsan sapien, eget interdum neque nulla id mi. Donec massa quam, posuere ac blandit in, molestie non tellus. Aenean ut tempor massa. Fusce eget sodales diam, non faucibus erat. Aenean tempor ligula sit amet urna hendrerit accumsan id nec dui. Sed felis nulla, mattis ornare pellentesque a, placerat a nibh. Maecenas eget ante vitae nibh suscipit ornare. Donec eu erat sed quam dapibus pretium.
    
    Ut mattis molestie nulla, a congue purus placerat id. Sed scelerisque risus vel blandit sollicitudin. In massa neque, sagittis ac nulla sagittis, viverra vulputate est. Vestibulum a neque semper, euismod quam vel, efficitur lorem. Nunc vitae enim ante. Interdum et malesuada fames ac ante ipsum primis in faucibus. Sed turpis velit, sodales in pulvinar sit amet, mollis lobortis nunc. Maecenas ante dolor, feugiat viverra mi non, pretium rutrum ligula. Curabitur in congue sem.
    
    Morbi at odio ut elit sagittis vehicula vel ut nisi. Vestibulum sed dui sed dui mollis mattis in et lorem. Suspendisse potenti. Fusce ultrices sem leo, id porttitor urna lobortis luctus. Vivamus in sapien accumsan, laoreet quam quis, tincidunt turpis. Phasellus at nibh a erat vehicula ornare. Vivamus sodales commodo aliquet. Integer auctor rutrum est sit amet viverra. Pellentesque egestas urna non magna lacinia, aliquam viverra urna suscipit. Etiam dictum, quam ut dapibus maximus, nibh risus elementum elit, et aliquet lorem ligula et leo. Nam pulvinar porttitor arcu. Mauris euismod velit eu condimentum blandit. Donec vehicula congue viverra.';




$id = 156;

//db에 저장된 fiction 장르를 가져온다
$board_name = 'community';

$genre ='';
$sql_genre = "SELECT*FROM novelProject_boardInfo WHERE name='$board_name'";
$result_genre = mysqli_query($db, $sql_genre);

//string으로 이어서 가져온 장르를 개별로 분할하여 화면에 출력한다
if(mysqli_num_rows($result_genre)==1){
    $row_genre = mysqli_fetch_array($result_genre);
    $genre_string = $row_genre['category'];
}
$genre_split_array = explode(';', $genre_string);


for($i=0; $i<248; $i++){


//    $title ='';
//    $sql_title = "SELECT title FROM novelProject_nonfiction WHERE id='$id'";
//    $result_title = mysqli_query($db, $sql_title);
//    if(mysqli_num_rows($result_title) ==1){
//        $title = mysqli_fetch_array($result_title)['title'];
//    }
//    $title_noSpace = trim($title);
//    $title_modified = str_replace('\'','',$title_noSpace);
//    $title_modified_final = explode('(', $title)[0];


    $max_num = count($genre_split_array)-1;
    $random_number = mt_rand(0, $max_num);
    $random_genre = $genre_split_array[$random_number];

    $description = 'Quisque pulvinar diam at felis vulputate, non gravida dui varius.';
    $date = date('Y/m/d H:i:s', strtotime( '-'.mt_rand(0,1800).' days'));
    $numberOfComments = 0;

    $rand_num = mt_rand(1, 9);
    $numberOfViews = mt_rand($rand_num*500, $rand_num*500+600);
    $numberOfLikes = mt_rand($rand_num*40, $rand_num*40+100);
    $bookmark = mt_rand($rand_num*10, $rand_num*10+30);


//    $sql_update = "UPDATE novelProject_nonfiction SET title='$title_noSpace' WHERE id ='$id'";

    $sql_update = "UPDATE novelProject_nonfiction SET genre='$random_genre', title='haha' WHERE id ='$id' or die(mysqli_error($db))";

    $result = mysqli_query($db, $sql_update);

//    $id += 1;
}


//$id = 708;
//$title ='';
//
//for($i=0; $i<250; $i++){
//
//    $sql_title = "SELECT author_username FROM novelProject_nonfiction WHERE id='$id'";
//    $result_title = mysqli_query($db, $sql_title);
//    if(mysqli_num_rows($result_title) ==1){
//        $title = mysqli_fetch_array($result_title)['author_username'];
//    }
//    $title_noSpace = trim($title);
//    $title_modified = str_replace('\'','',$title_noSpace);
//
//
//    $sql_update = "UPDATE novelProject_nonfiction SET author_username ='$title_modified' WHERE id ='$id'";
//    $result = mysqli_query($db, $sql_update);
//
//    $id += 1;
//}


//$id = 708;
//$title ='';
//$author_name = '';
//
//for($i=0; $i<250; $i++){
//
//    $sql_author = "SELECT author_username FROM novelProject_nonfiction WHERE id='$id'";
//    $result_author = mysqli_query($db, $sql_author);
//    if(mysqli_num_rows($result_author) ==1){
//        $author_name = mysqli_fetch_array($result_author)['author_username'];
//    }
//    $author_email = explode(' ', $author_name)[0];
//    $author_email_lowercase = strtolower($author_email).'@gmail.com';
//
//
//    $sql_update = "UPDATE novelProject_nonfiction SET author_email ='$author_email_lowercase' WHERE id ='$id'";
//    $result = mysqli_query($db, $sql_update);
//
//    $id += 1;
//}



//$id = 1047;
//$title ='';
//
//for($i=0; $i<100; $i++){
//
//
//    $image = mt_rand(1, 25).'.jpg';
//
//    $sql_update = "UPDATE novelProject_storyInfo SET image ='$image' WHERE id ='$id'";
//    $result = mysqli_query($db, $sql_update);
//
//    $id += 1;
//}




//    $story_db_id=1047;
//
//    for($i=0; $i<100; $i++){
//
//        $random_numberOfEpisodes = mt_rand(20, 50);
//
//        for($k=0; $k<=$random_numberOfEpisodes; $k++){
//
//            if($k==0){
//                $episodeTitle = 'Epilogue';
//            }else{
//                $episodeTitle = 'Chapter '.$k;
//            }
//
//
//            //story 정보를 가져온다
//            $sql_storyInfo = "SELECT*FROM novelProject_storyInfo WHERE id='$story_db_id'";
//
//            $result = mysqli_query($db, $sql_storyInfo);
//
//            $storyTitle='';
//            $storyGenre='';
//            $author_username='';
//            $author_email='';
//            $numberOfEpisode='';
//            if(mysqli_num_rows($result)==1){
//                $row = mysqli_fetch_array($result);
//
//                $storyTitle = $row['title'];
//                $storyGenre=$row['genre'];
//                $author_username=$row['author_username'];
//                $author_email=$row['author_email'];
//                $numberOfEpisode=$row['numberOfEpisode']+1;
//            }
//
//            $period = $random_numberOfEpisodes - $k;
//            $time = date('Y-m-d H:i:s', strtotime( '-'.$period.' days'));
//
//            $date_modified = explode(' ',$time)[0];
//
//
//            $rand_num = mt_rand(1, 9);
//            $numberOfViews = mt_rand($rand_num*600, $rand_num*600+600);
//            $numberOfLikes = mt_rand($rand_num*70, $rand_num*70+140);
//            $bookmark = mt_rand($rand_num*20, $rand_num*20+40);
//
//            $sql_episodeInfo = "INSERT INTO novelProject_episodeInfo(genre, title, content, storyTitle, author_email, author_username, date, story_db_id, numberOfViews, numberOfComments, numberOfLikes, bookmark)VALUES
//        ('$storyGenre','$episodeTitle','$content','$storyTitle','$author_email','$author_username','$time','$story_db_id', '$numberOfViews', 0, '$numberOfLikes', '$bookmark')";
//
//
//            if($k==0){
//                $sql_storyInfo = "UPDATE novelProject_storyInfo SET startDate='$date_modified', lastUpdate='$date_modified', numberOfEpisode='$numberOfEpisode' WHERE id='$story_db_id'";
//            }else{
//                $sql_storyInfo = "UPDATE novelProject_storyInfo SET lastUpdate='$date_modified', numberOfEpisode='$numberOfEpisode' WHERE id='$story_db_id'";
//            }
//
//            //story db 업데이트
//            $result_storyDB = mysqli_query($db, $sql_storyInfo) or die(mysqli_error($db));
//
//            //episode 저장
//            $result_episodeDB = mysqli_query($db, $sql_episodeInfo) or die(mysqli_error($db));
//
//        }
//
//        $story_db_id += 1;
//    }

