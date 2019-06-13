<?php

    //댓글의 고유 코드를 생성하는 함수
    function generateRandomString($length = 6){
        $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
        $characterLength = count($characters);
        $randomString = '';

        for($i = 0; $i < $length; $i++){
            //rand(최소값, 최대값)
            $randomString .= $characters[mt_rand(0, $characterLength -1)];
        }
        return $randomString;
    }

    ?>