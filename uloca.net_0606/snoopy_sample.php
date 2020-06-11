<?
//일반 페이지 호출

 $snoopy = new Snoopy;

 $url = "http://example.com";

 $snoopy->fetch($url);
 $list_result = $snoopy->results;

//로그인 페이지 호출

 $snoopy = new Snoopy;

 $url_login = "http://example.com/login_act.php";

 $login_data = array(
       "user_id"      => $userid
      , "password"     => $userpw
     );
 $snoopy->submit($url_login,$login_data);

 $snoopy -> setcookies();

 $snoopy->fetch($url);
 $list_result = $snoopy->results;

 

 



//출처: http://jajang22.tistory.com/1 [권이네 일상]
?>