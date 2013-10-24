<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
class DashboardpronewslistController extends Controller {
	
	public $num = 15;
	
	public $helpers = array('html','form');
	
	public function on_start() {
		Loader::model('page_list');
		$this->error = Loader::helper('validation/error');
	}
	
	public function view() {
		$this->loadnewsSections();
		$newsList = new PageList();
		$newsList->sortBy('cDateAdded', 'desc');
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
            
			$newsList->setItemsPerPage($this->num);
		    
		$newsResults=$newsList->getPage();
		
			if(!empty($_GET['cat'])){
				$pageList = $newsResults;
				$newsResults = array();
				foreach($pageList as $page){
				    $filter_catpage = $page->getCollectionAttributeValue('news_category');
				    foreach($filter_catpage as $catpage){
  	 				if($catpage->value == $_GET['cat']) {
      					array_push($newsResults, $page);   
   					}
   					}
				}
				
			}
			if(!empty($_GET['tag'])){
				$pageList = $newsResults;
				$newsResults = array();
				foreach($pageList as $page){
				$filter_tagpage = $page->getCollectionAttributeValue('news_tag');
				foreach($filter_tagpage as $tagpage)
  	 				if($tagpage->value == $_GET['tag']) {
      					array_push($newsResults, $page);   
   					}
				}
				
			}
				if(!empty($_GET['dist'])){
				$pageList = $newsResults;
				$newsResults = array();
				foreach($pageList as $page){
				    $filter_dispage = $page->getCollectionAttributeValue('district');
				    foreach($filter_dispage as $dispage){				
  	 				if($dispage->value == $_GET['dist']) {
      					array_push($newsResults, $page);   
   					}
   					}
				}
				
			}
			
			
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
	
	public function approvethis($cIDd,$name) {
		$p = Page::getByID($cIDd);
		$p->setAttribute('approve','1');;
		$this->set('message', t('"'.$name.'" has been approved and is now public')); 
		$this->view();
	}
	
	public function unapprovethis($cIDd,$name) {
		$p = Page::getByID($cIDd);
		$p->setAttribute('approve','0');;
		$this->set('message', t('"'.$name.'" has been approved and is now public')); 
		$this->view();
	}
	
	public function delete_check($cIDd,$name) {
		$this->set('remove_name',$name);
		$this->set('remove_cid',$cIDd);
		$this->view();
	}
	
	public function deletethis($cIDd,$name) {
		$c= Page::getByID($cIDd);
		$c->delete();
		$this->set('message', t('"'.$name.'" has been deleted')); 
		$this->set('remove_name','');
		$this->set('remove_cid','');
		$this->view();
	}
	
	public function clear_warning(){
		$this->set('remove_name','');
		$this->set('remove_cid','');
		$this->view();
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
	
	
	public function news_added() {
		$this->set('message', t('News added.'));
		$this->view();
	}
	
	public function news_updated() {
		$this->set('message', t('News updated.'));
		$this->view();
	}
	
	
	
}