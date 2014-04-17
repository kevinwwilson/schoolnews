<?php
defined('C5_EXECUTE') or die("Access Denied.");

class SearchBlockController extends Concrete5_Controller_Block_Search {
    	function do_search() {
		$q = $_REQUEST['query'];
		// i have NO idea why we added this in rev 2000. I think I was being stupid. - andrew
		// $_q = trim(preg_replace('/[^A-Za-z0-9\s\']/i', ' ', $_REQUEST['query']));
		$_q = $q;
		Loader::library('database_indexed_search');
		$ipl = new IndexedPageList();
		$aksearch = false;
		$ipl->ignoreAliases();
		if (is_array($_REQUEST['akID'])) {
			Loader::model('attribute/categories/collection');
			foreach($_REQUEST['akID'] as $akID => $req) {
				$fak = CollectionAttributeKey::getByID($akID);
				if (is_object($fak)) {
					$type = $fak->getAttributeType();
					$cnt = $type->getController();
					$cnt->setAttributeKey($fak);
					$cnt->searchForm($ipl);
					$aksearch = true;
				}
			}
		}

		if (isset($_REQUEST['month']) && isset($_REQUEST['year'])) {
			$month = strtotime($_REQUEST['year'] . '-' . $_REQUEST['month'] . '-01');
			$month = date('Y-m-', $month);
			$ipl->filterByPublicDate($month . '%', 'like');
			$aksearch = true;
		}


		if (empty($_REQUEST['query']) && $aksearch == false) {
			return false;
		}

		$ipl->setSimpleIndexMode(true);
		if (isset($_REQUEST['query'])) {
			$ipl->filterByKeywords($_q);
		}

		if( is_array($_REQUEST['search_paths']) ){
			foreach($_REQUEST['search_paths'] as $path) {
				if(!strlen($path)) continue;
				$ipl->filterByPath($path);
			}
		} else if ($this->baseSearchPath != '') {
			$ipl->filterByPath($this->baseSearchPath);
		}

		$ipl->filter(false, '(ak_exclude_search_index = 0 or ak_exclude_search_index is null)');

                $b = Block::getByID($this->bID);
                $template = strtolower($b->getBlockFilename());

                //if this is for display on the news search page
                if($template=='news_search'){
                    //make sure that we're only showing published articles
                    $ipl->filter(false,"ak_group_status like '%Published%'");
                }

		$res = $ipl->getPage();

		foreach($res as $r) {
			$results[] = new IndexedSearchResult($r['cID'], $r['cName'], $r['cDescription'], $r['score'], $r['cPath'], $r['content']);
		}

		$this->set('query', $q);
		$this->set('paginator', $ipl->getPagination());
		$this->set('results', $results);
		$this->set('do_search', true);
		$this->set('searchList', $ipl);
	}
}