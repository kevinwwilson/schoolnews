<?php

defined('C5_EXECUTE') or die("Access Denied.");

class NewsLoaderHelper {

    public function getHomeNews() {
        $db = Loader::db();
        $nh = Loader::helper('navigation');
        Loader::model('article');
        Loader::model('page_list');

        $row = $db->GetArray('SELECT * FROM btselectProNewsList');
        foreach ($row as $data) {
            if ($data['active'] == 1) {
                $active_artid = $data['atID'];
            }
        }
        $articleids = explode('||', $active_artid);
        shuffle($articleids);
        $m = 0;
        $pageArray = array();
        foreach ($articleids as $displayid) {
            $page = Page::getByID($displayid, $version = 'ACTIVE');
            $article = new article();
            $article->getArticleAttributes($page);
            $pageArray[] = $article;
        }
        return $pageArray;
    }

    public function getSeriesList() {
        Loader::model('page_list');
        Loader::model('series_list');
        $pl = new PageList();
        $pl->filterByAttribute('series_list', 1);
        if (count($pl > 0)) {
            $seriesList = $pl->get();
            //Load the c5 page list into a set of custom SeriesList models
            return seriesList::buildFromPageList($seriesList);
        }
    }

}
