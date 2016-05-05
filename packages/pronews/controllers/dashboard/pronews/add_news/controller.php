<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
class DashboardPronewsAddNewsController extends Controller {


	public $helpers = array('html','form');

	public function on_start() {

	   Loader::model('config');
	    $this->token = Loader::helper('validation/token');
	    $this->set('page_type_id',Config::get('PAGE_TYPE_ID'));
	    $this->set('sections_id',Config::get('SECTIONS_ID'));

		Loader::model('page_list');
		$this->error = Loader::helper('validation/error');
	}

	public function view() {
		$this->setupForm();
		$this->loadnewsSections();
		$newsList = new PageList();
		$newsList->sortBy('cDateAdded', 'desc');
		if (isset($_GET['cParentID']) && $_GET['cParentID'] > 0) {
			$newsList->filterByParentID($_GET['cParentID']);
		} else {
			$sections = $this->get('sections');
			$keys = array_keys($sections);
			$keys[] = -1;
			$newsList->filterByParentID($keys);
		}


	}


	protected function loadnewsSections() {
		$newsSectionList = new PageList();
		$newsSectionList->filterByNewsSection(1);
		$newsSectionList->sortBy('cvName', 'asc');
		$tmpSections = $newsSectionList->get();
		$sections = array();
		foreach($tmpSections as $_c) {
			$sections[$_c->getCollectionID()] = $_c->getCollectionName();
		}
		$this->set('sections', $sections);
	}


	public function edit($cID, $redirect = null) {
		$this->setupForm();
		$news = Page::getByID($cID, 'ACTIVE');
		if ($this->isPost()) {
			$this->validate();
			if (!$this->error->has()) {
				$p = Page::getByID($this->post('newsID'), 'ACTIVE');
				$parent = Page::getByID($this->post('cParentID'));
				$ct = CollectionType::getByID($this->post('ctID'));
				$data = array('ctID' =>$ct->getCollectionTypeID(), 'cDescription' => $this->post('newsDescription'), 'cName' => $this->post('newsTitle'), 'cDatePublic' => Loader::helper('form/date_time')->translate('newsDate'));
				$p->update($data);
				if ($p->getCollectionParentID() != $parent->getCollectionID()) {
					$p->move($parent);
				}
				$this->saveData($p);
                                if ($redirect == 'return') {
                                    $this->set('news', $news);
                                    $this->set('message', t('News updated.'));
                                    $this->view();
                                } else {
                                    $this->redirect('/dashboard/pronews/list/', 'news_updated');
                                }
                            }
		}

		$sections = $this->get('sections');
		if (in_array($news->getCollectionParentID(), array_keys($sections))) {
			$this->set('news', $news);
		} else {
			$this->redirect('/dashboard/pronews/add_news/');
		}

	}

	protected function setupForm() {
		$this->loadnewsSections();

		Loader::model("collection_types");
		$ctArray = CollectionType::getList('');
		$pageTypes = array();
		foreach($ctArray as $ct) {
			$pageTypes[$ct->getCollectionTypeID()] = $ct->getCollectionTypeName();
		}

		Loader::model('author_list');
		$authorList = new AuthorList();
		$authorList->loadValues();

		$this->set('pageTypes', $pageTypes);
		$this->addHeaderItem(Loader::helper('html')->javascript('tiny_mce/tiny_mce.js'));
	}

	public function add($redirect = null) {
		$this->setupForm();
                $urlHelper = Loader::helper('url');
		if ($this->isPost()) {
			$this->validate();
			if (!$this->error->has()) {
				$parent = Page::getByID($this->post('cParentID'));
				$ct = CollectionType::getByID($this->post('ctID'));
                                $handle = preg_replace('/[^a-z]+/i', '-', $this->post('newsTitle'));
				$data = array('cName' => $this->post('newsTitle'), 'cHandle' => $handle, 'cDescription' => $this->post('newsDescription'), 'cDatePublic' => Loader::helper('form/date_time')->translate('newsDate'));
				$p = $parent->add($ct, $data);
				$p->setAttribute('group_status', "Draft");
                                //$p = Page::add($ct, $data);
				$this->saveData($p);
                                $p->reindex();

                                if ($redirect == 'return') {
                                    $redirectUrl  = '/dashboard/pronews/add_news/edit/' . $p->getCollectionID();
                                    $this->set('message', t('News Added.'));
                                    $this->redirect($redirectUrl);
                                } else {
                                    $this->redirect('/dashboard/pronews/list/', 'news_updated');
                                }
			}
		}
	}

	public function delete_check($cIDd) {
	    $this->set('remove_name','This news');
		$this->set('remove_cid',$cIDd);
		$this->setupForm();
		$news = Page::getByID($cIDd, 'ACTIVE');

		if ($this->isPost()) {
			$this->validate();
			if (!$this->error->has()) {
				$p = Page::getByID($this->post('newsID'), 'ACTIVE');
				$parent = Page::getByID($this->post('cParentID'));
				$ct = CollectionType::getByID($this->post('ctID'));
				$data = array('ctID' =>$ct->getCollectionTypeID(), 'cDescription' => $this->post('newsDescription'), 'cName' => $this->post('newsTitle'), 'cDatePublic' => Loader::helper('form/date_time')->translate('newsDate'));
				$p->update($data);
				if ($p->getCollectionParentID() != $parent->getCollectionID()) {
					$p->move($parent);
				}
				$this->saveData($p);
				$this->redirect('/dashboard/pronews/list/', 'news_updated');
			}
		}

		$sections = $this->get('sections');
		if (in_array($news->getCollectionParentID(), array_keys($sections))) {
			$this->set('news', $news);
		} else {
			$this->redirect('/dashboard/pronews/add_news/');
		}

		$this->view();
	}

	public function delete_news($cIDd) {
	    $db = Loader::db();
	    $akID = $db->query("SELECT * FROM btselectProNewsList");
	    $row=$akID->getarray();

	    $groupids = array();
	    foreach($row as $data){


	      $cId = $data['ID'];
		  $groupid = $data['atID'];


		  $groupids = explode("||", $groupid);



		  $i = 0;
		   foreach($groupids as $gid){
			   if($cIDd == $gid)
			   {
				unset($groupids[$i]);
				$upgroupid = implode("||", $groupids);

				$sql = $db->query("UPDATE btselectProNewsList SET atID='$upgroupid' WHERE ID='$cId'");
	   $db->Execute($sql);

			   }

			  $i++;
		   }


	    }

	    $c= Page::getByID($cIDd, 'ACTIVE');
		$c->delete();
		$this->set('message', t('News has been deleted'));
	    $this->redirect('/dashboard/pronews/list/','news_deleted');

	    $db = Loader::db();




	}

	protected function validate() {
		$vt = Loader::helper('validation/strings');
		$vn = Loader::Helper('validation/numbers');
		$dt = Loader::helper("form/date_time");
		if (!$vn->integer($this->post('cParentID'))) {
			$this->error->add(t('You must choose a parent page for this news entry.'));
		}

		if (!$vn->integer($this->post('ctID'))) {
			$this->error->add(t('You must choose a page type for this news entry.'));
		}

		if (!$vt->notempty($this->post('newsTitle'))) {
			$this->error->add(t('Title is required'));
		}

		Loader::model("attribute/categories/collection");


		$akct = CollectionAttributeKey::getByHandle('news_category');
		$ctKey = $akct->getAttributeKeyID();
		foreach($this->post(akID) as $key => $value){
			if($key==$ctKey){
				foreach($value as $type => $values){
					if($type=='atSelectNewOption'){
						foreach($values as $cat => $valued){
							if($valued==''){
								$this->error->add(t('Categories must have a value'));
							}
						}
					}
				}
			}
		}

		if (!$this->error->has()) {
			Loader::model('collection_types');
			$ct = CollectionType::getByID($this->post('ctID'));
			$parent = Page::getByID($this->post('cParentID'));
			$parentPermissions = new Permissions($parent);
			if (!$parentPermissions->canAddSubCollection($ct)) {
				$this->error->add(t('You do not have permission to add a page of that type to that area of the site.'));
			}
		}

		if($this->error->has()){
			if($this->post('newsID')!=''){
				$mode = 'edit/'.$this->post('newsID').'/';
			}else{
				$mode = '';
			}
			$this->set('message', t('<a href="'.BASE_URL.'/index.php/dashboard/pronews/add_news/'.$mode.'"><input type="button" value="continue"/></a>'));
		}
	}
	private function saveData($p) {


		$blocks = $p->getBlocks('Main');
		foreach($blocks as $b) {
			$b->deleteBlock();
		}

		Loader::model("attribute/categories/collection");
		/*$cak = CollectionAttributeKey::getByHandle('news_tag');
		$cak->saveAttributeForm($p);*/

		$cck = CollectionAttributeKey::getByHandle('meta_title');
		$cck->saveAttributeForm($p);

		$cck = CollectionAttributeKey::getByHandle('meta_description');
		$cck->saveAttributeForm($p);

		$cck = CollectionAttributeKey::getByHandle('meta_keywords');
		$cck->saveAttributeForm($p);

		$cck = CollectionAttributeKey::getByHandle('news_category');
		$cck->saveAttributeForm($p);

		$cnv = CollectionAttributeKey::getByHandle('exclude_nav');
		$cnv->saveAttributeForm($p);

		$ct = CollectionAttributeKey::getByHandle('thumbnail');
		$ct->saveAttributeForm($p);

		$cur = CollectionAttributeKey::getByHandle('news_url');
		$cur->saveAttributeForm($p);

		$p->setAttribute('secondary_headline',$this->post('secondaryHeadline'));
		$p->setAttribute('author',$this->post('author'));
		$p->setAttribute('long_summary',$this->post('longSummary'));

		$p->setAttribute('main_photo',$this->post('mainPhoto'));
		$p->setAttribute('photo_caption',$this->post('photoCaption'));
		$p->setAttribute('dateline',$this->post('dateline'));
		$p->setAttribute('district',$this->post('district'));
		$p->setAttribute('story_slug',$this->post('storySlug'));
		$p->setAttribute('regional_feature',$this->post('regionalFeature'));
		$p->setAttribute('news_tag',$this->post('newsTag'));
		$p->setAttribute('single_multiple_photo_status',$this->post('singlemultiple'));


		$p->setAttribute('files',$this->post('files'));
		$bt = BlockType::getByHandle('content');
		$test = $this->post('files');
		$main_image=$p->getAttribute('main_photo');
		if($main_image != ''){

		$fv = $main_image->getApprovedVersion();

		}
		$dtag = $p->getAttribute('dateline');
		$ddisc = $p->getAttribute('story_slug');


		foreach($dtag as $tags){
		if($main_image != ''){
		$fv->updateTags($tags->value);
		}
		}
		if($main_image != ''){
		$fv->updateDescription($ddisc);

		  $f_id = $main_image->fID;
		  if (is_object($f_id)) {
          $f_id = $f_id->getFileID();
          }
          $file_set_file = FileSetFile::createAndGetFile($f_id,'9');
        }



		$data = array('content' => $this->post('newsBody'));

		$b = $p->addBlock($bt, 'Main', $data);
		$b->setCustomTemplate('news_post');

		$p->reindex();

	}


	public function on_before_render() {
		$this->set('error', $this->error);
	}

	public function loadAuthors() {
		$db = Loader::db();
        $akID = $db->query("SELECT akID FROM AttributeKeys WHERE akHandle = 'regular_author'");
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
}
