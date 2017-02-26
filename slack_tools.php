<?php
function slack($txt) {
  $message = array('payload' => json_encode(array('text' => $txt)));
  $c = curl_init(SLACK_WEBHOOK);
  curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($c, CURLOPT_POST, true);
  curl_setopt($c, CURLOPT_POSTFIELDS, $message);
  curl_exec($c);
  curl_close($c);
}

function read_mails($start=1) {
	$mb = imap_open("{".IMAP_SERVER.":143/imap}",IMAP_USER, IMAP_PASSWORD );
	$ret = array();
	$messageCount = imap_num_msg($mb);
	for( $MID = $start; $MID <= $messageCount; $MID++ )
	{
	   $headers = imap_headerinfo( $mb, $MID );
	   $body = imap_fetchbody( $mb, $MID, 1 );
	   $ret[]=array('mid'=>$MID,'body'=>$body,'subject'=>$headers->subject,'from'=>$headers->from,'to'=>$headers->to,'date'=>$headers->date,'udate'=>$headers->udate);
	}
	imap_close($mb);
	return $ret;
}

function conf_load() {
	return json_decode(file_get_contents('config.json'),true);
}

function conf_save($c) {
	file_put_contents('config.json',json_encode($c));
}

function slack_mails() {
  $c=conf_load();
  $mid=array_key_exists('mid',$c) ? $c['mid'] : 0;
  $mid += 1;
  $mails=read_mails($mid);
  echo ('<h1>'.sizeof($mails).' new mails </h1>');
  foreach($mails as $m)
  {
    slack('New Mail: '.$m['subject']);
    echo ($m['subject'].'<br />');
  }
  if ($mails)
  {
    $c['mid']=end($mails);
    $c['mid']=$c['mid']['mid'];
    conf_save($c);
  }
}

$c=conf_load();
define('SLACK_WEBHOOK', $c['slack_webhook']);
define('IMAP_SERVER', $c['imap_server']);
define('IMAP_USER', $c['imap_user']);
define('IMAP_PASSWORD', $c['imap_password']);
?>
