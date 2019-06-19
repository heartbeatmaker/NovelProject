<?php
//자주 사용하는 메소드를 저장해놓는 곳

//댓글의 고유 코드를 생성하는 함수
function generateRandomString($length = 6)
{
    $characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
    $characterLength = count($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        //rand(최소값, 최대값)
        $randomString .= $characters[mt_rand(0, $characterLength - 1)];
    }
    return $randomString;
}


function generateRandomInt($max){
    $randomNum = mt_rand(1, $max);
    return $randomNum;
}



?>