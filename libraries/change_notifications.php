<?php defined('C5_EXECUTE') or die("Access Denied.");
$db = Loader::db();		 
$row = $db->GetArray("SELECT * FROM btselectProNewsList");

$today = getdate();
$year = $today['year'];
$month = $today['mon'];
$day = $today['mday'];
$hour = $today['hours'];
$min = $today['minutes'];
$current_time = $year.'-'.$month.'-'.$day.' '.$hour.':'.$min.':00';

foreach($row as $data){
 $curid = $data['ID']; 
 $ctime = $data['time']; 
 
        $lesstime = array();
		if($current_time > $ctime){		
		$lesstime[] = $ctime;
			
		}
		
}

$largevalue = max($lesstime);


	   $sql = $db->query("UPDATE btselectProNewsList SET active='1' WHERE time='$largevalue'");
?>