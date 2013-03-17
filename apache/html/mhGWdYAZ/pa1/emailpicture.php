<?php
  $url = $_GET['url'];
  $to = $_GET['email'];
  $from = "EECS485 Group6 Webservant <webservant@eecs485-02.eecs.umich.edu>";
  $subject = "Master, here is your lovely picture";
  $separator = md5(time());
  $message = "Master, here is the picture you choose. Enjoy it!";
  $eol = PHP_EOL;   //   "\r\n"


  $attachment = chunk_split(base64_encode(file_get_contents($url)));
  $headers = "From: ".$from.$eol;
  $headers .= "MIME-Version: 1.0".$eol;
  $headers .= "Content-Type: multipart/mixed; boundary=\"".$separator
    ."\"";

  $body = "--".$separator.$eol;
  $body .= "Content-Transfer-Encoding: 7bit".$eol.$eol;
  $body .= "This is a MIME encoded message.".$eol;

  //message
  $body .= "--".$separator.$eol;
  $body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
  $body .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
  $body .= $message.$eol;

  //attachment
  $body .= "--".$separator.$eol;
  $body .= "Content-Type: application/octet-stream; name=\""
    .$url."\"".$eol;
  $body .= "Content-Transfer-Encoding: base64".$eol;
  $body .= "Content-Disposition: attachment".$eol.$eol;
  $body .= $attachment.$eol;
  $body .= "--".$separator."--";


  mail($to, $subject, $body, $headers);

  echo "Email Sent to $to";
?>



