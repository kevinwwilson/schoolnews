<?php  

	defined('C5_EXECUTE') or die(_("Access Denied."));
	class PronewsListBlockController extends BlockController {

		protected $btTable = 'btProNewsList';
		protected $btInterfaceWidth = "500";
		protected $btInterfaceHeight = "350";
		
		/** 
		 * Used for localization. If we want to localize the name/description we have to include this
		 */
		public function getBlockTypeDescription() {
			return t("List News Items based on type, area, or category.");
		}
		
		public function getBlockTypeName() {
			return t("ProNews List");
		}
		
		public function getJavaScriptStrings() {
			return array(
				'feed-name' => t('Please give your RSS Feed a name.')
			);
		}
		
		function getPages($query = null) {
			Loader::model('page_list');
			$db = Loader::db();
			$bID = $this->bID;
			if ($this->bID) {
				$q = "select * from btProNewsList where bID = '$bID'";
				$r = $db->query($q);
				if ($r) {
					$row = $r->fetchRow();
				}
			} else {
				$row['num'] = $this->num;
				$row['cParentID'] = $this->cParentID;
				$row['cThis'] = $this->cThis;
				$row['orderBy'] = $this->orderBy;
				$row['ctID'] = $this->ctID;
				$row['rss'] = $this->rss;
				$row['category'] = $this->category;
				$row['displayAliases'] = $this->displayAliases;
				$row['title'] = $this->title;
			}
			

			$pl = new PageList();
			$pl->setNameSpace('b' . $this->bID);
			
			$cArray = array();

			switch($row['orderBy']) {
				case 'display_asc':
					$pl->sortByDisplayOrder();
					break;
				case 'display_desc':
					$pl->sortByDisplayOrderDescending();
					break;
				case 'chrono_asc':
					$pl->sortByPublicDate();
					break;
				case 'alpha_asc':
					$pl->sortByName();
					break;
				case 'alpha_desc':
					$pl->sortByNameDescending();
					break;
				default:
					$pl->sortByPublicDateDescending();
					break;
			}

			$num = (int) $row['num'];
			
			if ($num > 0) {
				$pl->setItemsPerPage($num);
			}

			$c = Page::getCurrentPage();
			if (is_object($c)) {
				$this->cID = $c->getCollectionID();
			}
			$cParentID = ($row['cThis']) ? $this->cID : $row['cParentID'];
			
			if($this->displayArchive == 1){
				$date = date('Y-m-d',strtotime('-1 days',strtotime(date('Y-m-d'))));
				$pl->filterByPublicDate($date,'<=');
			}else{
				$pl->filterByPublicDate(date('Y-m-d H:i:s'),'<=');	
			}
			
			Loader::model('attribute/categories/collection');
			if ($this->displayFeaturedOnly == 1) {
				$cak = CollectionAttributeKey::getByHandle('is_featured');
				if (is_object($cak)) {
					$pl->filterByIsFeatured(1);
				}
			}
			
			if (!$row['displayAliases']) {
				$pl->filterByIsAlias(0);
			}
			$pl->filter('cvName', '', '!=');			
		
			if ($row['ctID']) {
				$pl->filterByCollectionTypeID($row['ctID']);
			}
			Loader::model('attribute/categories/collection');
			$columns = $db->MetaColumns(CollectionAttributeKey::getIndexedSearchTable());
			if (isset($columns['AK_EXCLUDE_PAGE_LIST'])) {
				$pl->filter(false, '(ak_exclude_page_list = 0 or ak_exclude_page_list is null)');
			}
			
			if ( intval($row['cParentID']) != 0) {
				$pl->filterByParentID($cParentID);
			}
			
			if ($this->category != 'All Categories') {
				$category = "\n$this->category\n";
				$pl->filterByNewsCategory($category,'LIKE');
			}	

			if ($num > 0) {
				$pages = $pl->getPage();
			} else {
				$pages = $pl->get();
			}
			$this->set('pl', $pl);
			return $pages;
		}
		
		public function view() {
			$cArray = $this->getPages();
			$nh = Loader::helper('navigation');
			$this->set('nh', $nh);
			//$this->set('cArray', $cArray);
			$this->set('cArray', $cArray);
		}
		
		public function add() {
			Loader::model("collection_types");
			$c = Page::getCurrentPage();
			$uh = Loader::helper('concrete/urls');
			//	echo $rssUrl;
			$this->set('c', $c);
			$this->set('uh', $uh);
			$this->set('bt', BlockType::getByHandle('pronews_list'));
			$this->set('displayAliases', true);
		}
	
		public function edit() {
			$b = $this->getBlockObject();
			$bCID = $b->getBlockCollectionID();
			$bID=$b->getBlockID();
			$this->set('bID', $bID);
			$c = Page::getCurrentPage();
			if ($c->getCollectionID() != $this->cParentID && (!$this->cThis) && ($this->cParentID != 0)) { 
				$isOtherPage = true;
				$this->set('isOtherPage', true);
			}
			$uh = Loader::helper('concrete/urls');
			$this->set('uh', $uh);
			$this->set('bt', BlockType::getByHandle('pronews_list'));
		}
		
		function save($args) {
			// If we've gotten to the process() function for this class, we assume that we're in
			// the clear, as far as permissions are concerned (since we check permissions at several
			// points within the dispatcher)
			$db = Loader::db();

			$bID = $this->bID;
			$c = $this->getCollectionObject();
			if (is_object($c)) {
				$this->cID = $c->getCollectionID();
			}
			
			$args['num'] = ($args['num'] > 0) ? $args['num'] : 0;
			$args['cThis'] = ($args['cParentID'] == $this->cID) ? '1' : '0';
			$args['cParentID'] = ($args['cParentID'] == 'OTHER') ? $args['cParentIDValue'] : $args['cParentID'];
			$args['truncateSummaries'] = ($args['truncateSummaries']) ? '1' : '0';
			$args['displayFeaturedOnly'] = ($args['displayFeaturedOnly']) ? '1' : '0';
			$args['displayAliases'] = ($args['displayAliases']) ? '1' : '0';
			$args['truncateChars'] = intval($args['truncateChars']); 
			$args['paginate'] = intval($args['paginate']); 
			$args['category'] = isset($args['category']) ? $args['category'] : '';
			$args['use_content'] = ($args['use_content']) ? '1' : '0';
			$args['title'] = isset($args['title']) ? $args['title'] : '';

			parent::save($args);
		
		}
		
		function getBlockPath($path){
			$db = Loader::db();
			$r = $db->Execute("SELECT cPath FROM PagePaths WHERE cID = '$path' ");
			while ($row = $r->FetchRow()) {
				$pIDp = $row['cPath'];
				return $pIDp ;
			}
		}

		public function getRssUrl($b){
			$uh = Loader::helper('concrete/urls');
			if(!$b) return '';
			$btID = $b->getBlockTypeID();
			$bt = BlockType::getByID($btID);
			$c = $b->getBlockCollectionObject();
			$a = $b->getBlockAreaObject();
			$rssUrl = $uh->getBlockTypeToolsURL($bt)."/rss?bID=".$b->getBlockID()."&cID=".$c->getCollectionID()."&arHandle=" . $a->getAreaHandle();
			return $rssUrl;
		}
	}

?>