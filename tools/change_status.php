

<?php 
defined('C5_EXECUTE') or die(_("Access Denied."));

$displayid = $_REQUEST['news_id'];


$selval = $_REQUEST['akID'][99]['atSelectOptionID'][0];
$db = Loader::db();
$option = $db->getone("SELECT value FROM atSelectOptions WHERE ID = $selval");
Loader::model('page_list');
$newsSectionList = new PageList();
$newsSectionList->filter(false, '( cv.cID in('.$displayid.') )');
$spages = $newsSectionList->getPage();


foreach($spages as $scpage){
$scpage->setAttribute('group_status',$option);
if($option == 'Published'){
	echo 'greensel';	
}elseif($option == 'Ready'){
    echo 'whitesel';	
}elseif($option == 'In Editing'){
	echo 'yellowesel';
	
}else{
	echo 'redesel';
}
die;

}


?>

</script>