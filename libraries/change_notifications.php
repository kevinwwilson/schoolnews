<?php defined('C5_EXECUTE') or die("Access Denied.");

$db = Loader::db();		 
$row = $db->GetArray("SELECT * FROM btselectProNewsList");
$current_time = date("Y-m-d H:i:s"); 
$lesstime = array();
foreach($row as $data){
 $curid = $data['ID']; 
 $ctime = $data['time']; 
        
		if($current_time > $ctime){
			
		array_push($lesstime, $ctime);				
}
}
$largevalue = max($lesstime);
$sqlz = $db->query("UPDATE btselectProNewsList SET active='0'");
$db->Execute($sqlz);
$sql = $db->query("UPDATE btselectProNewsList SET active='1' WHERE time='$largevalue'");
$db->Execute($sql);
?>