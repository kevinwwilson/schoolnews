<?php    
defined('C5_EXECUTE') or die(_("Access Denied."));
class DashboardPronewsSheduleNewsGroupController extends Controller {	

   public $helpers = array('html','form');
	
	public function on_start() {
		Loader::model('page_list');
		$this->error = Loader::helper('validation/error');
	}
	
	public function view() {
	$group=$this->group_list();
	$this->set('group', $group);
	$this->addHeaderItem(Loader::helper('html')->javascript('tiny_mce/tiny_mce.js'));
	
	}
	
	
	
	public function group_list() {
	
		 $db = Loader::db();		 
		 $row = $db->GetArray('SELECT * FROM btselectProNewsList ORDER BY time DESC');		 
		 return $row;
		 		 
	}
	
	
		 
		 public function group_added() {
		 
		 $this->set('message', t('Group added.'));
		 $this->view();
		 
		 }
		 
		 public function group_edited() {
		 
		 $this->set('message', t('Group updated.'));
		 $this->view();
		 
		 }
		 
		  public function group_deleted() {
		 
		 $this->set('message', t('Group deleted.'));
		 $this->view();
		 
		 }
	
	
	
	
}