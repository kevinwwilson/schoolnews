<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
class DashboardPronewsSheduleNewsGroupAddGroupController extends Controller {
	
	public $num = 15;
	
	public $helpers = array('html','form');
	
	public function on_start() {
		Loader::model('page_list');
		$this->error = Loader::helper('validation/error');
	}
	
	public function view() {
		$this->loadnewsSections();
		$newsList = new PageList();
		
		$seleart = $this->get('selart');
		
		if($seleart>0){
		   foreach($seleart as $sectarticles){
		   $sectarticlesid = explode("||",$sectarticles['atID']);
		   
		   foreach($sectarticlesid as $displayid){
		   
		   if($displayid != ''){
		    $newsList->filter(false, '( cv.cID not in('.$displayid.') )');
		    }
		    
		    }	   		   
		   
		   }
		}
		
		
		
		$newsList->sortBy('cvDatePublic', 'desc');
		$newsList->filter(false,"ak_group_status like '%Published%' or ak_group_status like '%Ready%'");
		
			if(isset($_GET['cParentID']) && $_GET['cParentID'] > 0){
			$newsList->filterByParentID($_GET['cParentID']);
			}
			if(empty($_GET['cParentID'])){
			$sections = $this->get('sections');
			$keys = array_keys($sections);
			$keys[] = -1;
			$newsList->filterByParentID($keys);
			}
			if(!empty($_GET['like'])){
			$newsList->filterByName($_GET['like']);
			}
			
			if(!empty($_GET['cat'])){
		    $cat = $_GET['cat'];
		    $newsList->filter(false,"ak_news_category like '%$cat%'");
		    } 
		    
		    if(!empty($_GET['tag'])){
		    $tag = $_GET['tag'];
		    $newsList->filter(false,"ak_news_tag like '%$tag%'");
		    }
		    
		    if(!empty($_GET['dist'])){
		    $dist = $_GET['dist'];
		    $newsList->filter(false,"ak_district like '%$dist%'");
		    } 
		    
		              
            
			$newsList->setItemsPerPage($this->num);
			
			
		    
		$newsResults=$newsList->getPage();
		
			
			
			
		$this->set('newsResults', $newsResults);
		$this->set('newsList', $newsList);
		$this->set('cat_values', $this->getNewsCats());
		$this->set('tag_values', $this->getNewsTags());
		$this->set('dist_values', $this->getDistict());
		
		
		
	}
	
	

	protected function loadnewsSections() {
		$newsSectionList = new PageList();
		$newsSectionList->setItemsPerPage($this->num);
		$newsSectionList->filterByNewsSection(1);
		$newsSectionList->sortBy('cvName', 'asc');
		$tmpSections = $newsSectionList->get();
		$sections = array();
		foreach($tmpSections as $_c) {
			$sections[$_c->getCollectionID()] = $_c->getCollectionName();
		}
		$this->set('sections', $sections);
	}	
	public function clear_warning(){
		$this->set('remove_name','');
		$this->set('remove_cid','');
		$this->view();
	}
	
	public function delete_group($cIDd){
	$db = Loader::db();
	$sql = $db->query("DELETE FROM btselectProNewsList WHERE ID='$cIDd'");
	
	$db->Execute($sql);	
	$this->redirect('/dashboard/pronews/shedule_news_group/','group_deleted');
		
	}
	
	
	public function getNewsCats(){
		$db = Loader::db();
		$akID = $db->query("SELECT akID FROM AttributeKeys WHERE akHandle = 'news_category'");
		while($row=$akID->fetchrow()){
			$akIDc = $row['akID'];
		}
		$akv = $db->execute("SELECT value FROM atSelectOptions WHERE akID = $akIDc");
		while($row=$akv->fetchrow()){
			$values[]=$row;
		}
		if (empty($values)){
			$values = array();
		}
		return $values;
	}
	
	
	public function getNewsTags(){
		$db = Loader::db();
		$akID = $db->query("SELECT akID FROM AttributeKeys WHERE akHandle = 'news_tag'");
		while($row=$akID->fetchrow()){
			$akIDc = $row['akID'];
		}
		$akv = $db->execute("SELECT value FROM atSelectOptions WHERE akID = $akIDc");
		while($row=$akv->fetchrow()){
			$values[]=$row;
		}
		if (empty($values)){
			$values = array();
		}
		return $values;
	}
	
	public function getDistict(){
		$db = Loader::db();
		$akID = $db->query("SELECT akID FROM AttributeKeys WHERE akHandle = 'district'");
		while($row=$akID->fetchrow()){
			$akIDc = $row['akID'];
		}
		$akv = $db->execute("SELECT value FROM atSelectOptions WHERE akID = $akIDc");
		while($row=$akv->fetchrow()){
			$values[]=$row;
		}
		if (empty($values)){
			$values = array();
		}
		return $values;
	}
	
	public function edit_group($cID) {
	
	$db = Loader::db();		 
    $row = $db->GetArray("SELECT * FROM btselectProNewsList WHERE ID = $cID");
    $this->set('selart', $row);
    $this->set('remove_cid',$cID);
	$this->view();
		
	}
	
	public function edit_groups($cID){
		
		$db = Loader::db();
	   $artclid = $this->post();
	   $atdate = $artclid['date_field_dt'];
	   $athour = $artclid['date_field_h'];
	   $atmin = $artclid['date_field_m'];
	   $atam = $artclid['date_field_a'];
	   $atexdate = explode('/',$atdate);
	   $atyear = $atexdate[1];
	   $atmonth = $atexdate[0];
	   $atday = $atexdate[2];
	   $ttime = ("$athour:$atmin $atam");
	   
	   $time_in_24_hour_format  = date("H:i", strtotime("$athour:$atmin $atam"));
	   
	   
	   $artid = $artclid['articlesar'];
	   
	   $artid=implode("||",$artid);	 
	   
	   
	   $sql = $db->query("UPDATE btselectProNewsList SET atID='$artid', time='$atday-$atmonth-$atyear $time_in_24_hour_format:00', active='' WHERE ID='$cID'");
	   
	   $db->Execute($sql);
		$this->redirect('/dashboard/pronews/shedule_news_group/','group_edited');	
		
		
	}
	
	public function save_group(){
	   $db = Loader::db();
	   $artclid = $this->post();	      
	   $atdate = $artclid['date_field_dt'];
	   $athour = $artclid['date_field_h'];
	   $atmin = $artclid['date_field_m'];
	   $atam = $artclid['date_field_a'];
	   $atexdate = explode('/',$atdate);
	   $atyear = $atexdate[1];
	   $atmonth = $atexdate[0];
	   $atday = $atexdate[2];
	   $ttime = ("$athour:$atmin $atam");
	   
	   $time_in_24_hour_format  = date("H:i", strtotime("$athour:$atmin $atam"));
	   
	   
	   $artid = $artclid['articlesar'];
	   
	   $artid=implode("||",$artid);	   
	   
	   $sql = $db->query("INSERT INTO btselectProNewsList (ID,atID,time,active) VALUES ('', '$artid','$atday-$atmonth-$atyear $time_in_24_hour_format:00','')");
	   $db->Execute($sql);
		$this->redirect('/dashboard/pronews/shedule_news_group/','group_added');		
	}	
	
		
	
	
}?>

