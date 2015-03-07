<?php defined('C5_EXECUTE') or die("Access Denied.");

$db = Loader::db();		 
$row = $db->GetArray("SELECT * FROM `btselectProNewsList` order by time desc");
$current_time = date("Y-m-d H:i:s");

if ($current_time < $row[0]['time'] || $row[0]['active'] == 1) {
    var_dump('doing nothing');
    // nothing to publish, don't do anything
} else {
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
            $page = Page::getByID($atids, $version = 'RECENT');
            $page->setAttribute('group_status', 'Published'); 
    //	Loader::model('page_list');
    //    $newsSectionList = new PageList();

    //    $spages = $newsSectionList->get(1000);
    //    foreach($spages as $scpage){    
    //    if($scpage->cID == $atids){    
    //    $scpage->setAttribute('group_status','Published');
    //    }   

        }		
    }
}


