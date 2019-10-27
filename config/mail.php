<?php
 
return [
  "driver" => "smtp",
  "host" => "smtp.mailtrap.io",
  "port" => 2525,
  "from" => array(
      "address" => "from@example.com",
      "name" => "Example"
  ),
  "username" => "af134acd5466a8" ,
  "password" => "5e4d2aef866771" , 
  "sendmail" => "/usr/sbin/sendmail -bs"
];
?>