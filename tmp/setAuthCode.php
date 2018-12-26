<?php

if(isset($_POST)){
 
  $authCode=$_POST["authCode"];
  echo putenv("AUTH_CODE=".$authCode);

}else{

}

?>