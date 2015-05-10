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

                        $b = Block::getByID($this->bID);
			$c = Page::getCurrentPage();
                        $template = strtolower($b->getBlockFilename());
                        
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
				$pl->filterByAttribute('news_category', "%$category%", 'LIKE');
			}


			if ($this->tagss != 'All Tags') {
				$tagss = "\n$this->tagss\n";
				//$pl->filterByAttribute('news_category', "%\n$category\n%", 'like');
				$pl->filterByAttribute('news_tag',"%$tagss%",'like');
				//$pl->filterByNewsCategory($category,'LIKE');
			}

                        

//                        $pl->filterByAttribute('district',"%$distss%",'like');
			
//                        //these are the templates where the "All Districts option should work and 
//                        //show all the districts
//                        $distss = "\n$this->distss\n";
                        if($template == 'pronews_list_thumbnails'){
                            if ($this->distss != 'All District') {
                                $distss = "\n$this->distss\n";
                                $pl->filter(false,"ak_district like '%$distss%' or ak_district like '%All Districts%'");
                            } else {
                                $pl->filter(false,"ak_district = 'All Districts'");
                            }
                        } else {
                            $pl->filterByAttribute('district',"%$distss%",'like');   
                        }

                        
			if($template=='home_images'){
			$pl->filterByAttribute('regional_feature',"%$this->category%",'like');
			}

			if($template=='left_side'){
			global $u;
			$pl->filterByAttribute('regional_feature',"%$this->category%",'like');
			}

                        if($template=='district_left_side'){
                        $pl->filter(false,"ak_group_status like '%Published%'");
			}

			if($template=='full_list'){
                                        global $u;
                            if (!$u -> isLoggedIn ()) {
                            $pl->filter(false,"ak_group_status like '%Published%'");

                            }
                            else{
                                    $pl->filter(false,"(ak_group_status like '%Published%' or ak_group_status like '%Ready%' or ak_group_status like '%In Editing%' or ak_group_status like '%Draft%')");
                                }
			}


			if($template=='home_main'){
			global $u;
                            if (!$u -> isLoggedIn ()) {
                            $pl->filter(false,"ak_group_status like '%Published%'");

                            }
                            else{
                                    $pl->filter(false,"ak_group_status like '%Published%' or ak_group_status like '%Ready%'");
                                }
			}

			if($template=='home_title'){
			global $u;
                            if (!$u -> isLoggedIn ()) {
                            $pl->filter(false,"ak_group_status like '%Published%'");

                            }
                            else{
                                    $pl->filter(false,"ak_group_status like '%Published%' or ak_group_status like '%Ready%'");
                                }
			}

			if($template=='pronews_list'){
			global $u;
			$category = $this->category;
			if (!$u -> isLoggedIn ()) {

                            $pl->filter(false,"((ak_group_status like '%Published%') and (ak_regional_feature not like '%".$category."%' or ak_regional_feature is NULL ))");
                            //$pl->filter(false,"ak_regional_feature not like '%$category%'");
                            //$pl->filterByAttribute('regional_feature',"%$category%",'not like');
                            }
                            else{
                                    $pl->filter(false,"((ak_group_status like '%Published%' or ak_group_status like '%Ready%') and (ak_regional_feature not like '%".$category."%' or ak_regional_feature is NULL ))");
                                    //$pl->filter(false,"((ak_group_status like '%Published%' or ak_group_status like '%Ready%') and (ak_regional_feature not like '%".$category."%'))");
                                    //$pl->filter(false,"ak_regional_feature not like '%$category%'");
                                    //$pl->filterByAttribute('regional_feature',"%$category%",'not like');
                                }

			}

                        if($template=='search') {

                            global $u;
                            if (!$u -> isLoggedIn ()) {
                            $pl->filter(false,"ak_group_status like '%Published%'");
                            } else {
                                $pl->filter(false,"ak_group_status like '%Published%' or ak_group_status like '%Ready%'");
                            }
                        }

			if($template=='full_article'){
                            global $u;
                            if (!$u -> isLoggedIn ()) {
                            $pl->filter(false,"ak_group_status like '%Published%'");

                            } else {
                                    $pl->filter(false,"(ak_group_status like '%Published%' or ak_group_status like '%Ready%')");
                            }
			}

                        $pl->filterByIsAlias(false);
                        
                        //we want to get the first page of the whole list before
                        //going and getting the specific page that is being requested
                        $first_page = clone $pl;

                        //returns a single item array
                        $first_page = $first_page->get(1, 0);
                        $this->set('first_page', $first_page[0]);

                        //now go and get pages
			if ($num > 0) {
				$pages = $pl->getPage();
			} else {
				$pages = $pl->get();
			}

			if($template=='search' && $_GET['q'] != ''){
                            $pages = $pl->get();
			}
                        $this->trackDisplayedArticles($pages);

			$this->set('pl', $pl);
			return $pages;
		}

		public function view() {
			$cArray = $this->getPages();
			$nh = Loader::helper('navigation');
			$this->set('nh', $nh);
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
                
                public function trackDisplayedArticles($pages)
                {
                    global $c;
                    $b = Block::getByID($this->bID);
                    $template = strtolower($b->getBlockFilename());

                    foreach ($pages as $page) {
                        if (!isset($c->displayedArticles[$page->getCollectionID()])) {
                            $c->displayedArticles[$page->getCollectionID()] = $template;
                        }
                    }
                }
	}

?>
