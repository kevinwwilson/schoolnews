<?php 

defined('C5_EXECUTE') or die("Access Denied.");

class Collection extends Concrete5_Model_Collection {
    		public function reindex($index = false, $actuallyDoReindex = true) {
			if ($this->isAlias()) {
				return false;
			}
			if ($actuallyDoReindex || ENABLE_PROGRESSIVE_PAGE_REINDEX == false) { 
				$db = Loader::db();
				
				Loader::model('attribute/categories/collection');
				$attribs = CollectionAttributeKey::getAttributes($this->getCollectionID(), $this->getVersionID(), 'getSearchIndexValue');
		
				$db->Execute('delete from CollectionSearchIndexAttributes where cID = ?', array($this->getCollectionID()));
				$searchableAttributes = array('cID' => $this->getCollectionID());
				$rs = $db->Execute('select * from CollectionSearchIndexAttributes where cID = -1');
				AttributeKey::reindex('CollectionSearchIndexAttributes', $searchableAttributes, $attribs, $rs);
				
				if ($index == false) {
					Loader::library('database_indexed_search');
					$index = new IndexedSearch();
				}
				
				$index->reindexPage($this);
				$db->Replace('PageSearchIndex', array('cID' => $this->getCollectionID(), 'cRequiresReindex' => 0), array('cID'), false);
                                
                                // added this as a hack - concrete 5 was not cooperating with setting this in the search index
                                $page = Page::getByID($this->getCollectionID());
                                $ak_g = CollectionAttributeKey::getByHandle('group_status');
                                $ak_r = CollectionAttributeKey::getByHandle('regional_feature');
                                          
                                $status = $page->getAttribute($ak_g);
                                $regional_feature = $page->getAttribute($ak_r);
                                
                                //These are both guaranteed to be an array of 1 by the select type
                                foreach ($status as $v) {
                                    $stat = $v->value;
                                }
                                
                                foreach ($regional_feature as $r) {
                                    $regional = $r->value;
                                }
                                
                                //force the value into the search index
                                $db->Replace('collectionsearchindexattributes', array('cID'=>$this->getCollectionID(), 'ak_group_status'=>$stat, 'ak_regional_feature' => $regional), array("cID"), true);
                                //  End hack of core code
                                
				$cache = PageCache::getLibrary();
				$cache->purge($this);

			} else { 			
				$db = Loader::db();
				Config::save('DO_PAGE_REINDEX_CHECK', true);
				$db->Replace('PageSearchIndex', array('cID' => $this->getCollectionID(), 'cRequiresReindex' => 1), array('cID'), false);
			}
		}
		
    
}