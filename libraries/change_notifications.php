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

$rows = $db->GetArray("SELECT * FROM btselectProNewsList WHERE time='$largevalue'");


foreach($rows as $actdata){


	$curids = $actdata['atID'];	
	$idsplit = explode('||', $curids);
	foreach($idsplit as $atids)
	{
	Loader::model('page_list');
    $newsSectionList = new PageList();
    //$newsSectionList->filter(false, '( cv.cID in('.$atids.') )');
    $spages = $newsSectionList->get(1000);
    foreach($spages as $scpage){    
    if($scpage->cID == $atids){    
    $scpage->setAttribute('group_status','Active');
    }   
    
    }
    	
		
	}
}



?>