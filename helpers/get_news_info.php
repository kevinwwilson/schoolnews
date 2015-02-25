<?php

defined('C5_EXECUTE') or die("Access Denied.");

class GetNewsInfoHelper {




	public static function getNewsFromJson()

        {

            $jsonNews = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/files/widget/news.json');

            $news = json_decode($jsonNews);

            return $news;

        }

        

        

        public static function getRecentNews($articles = 100)

        {

            Loader::model('page_list');

            $newsSection = new PageList();

            $newsSection->filterByNewsSection(1);

            $newsSection->filter(false,"ak_group_status like '%Published%'");

            

            $pageList = $newsSection->get();

            

            $pageIds = array();

            foreach ($pageList as $page) {

                $pageIds[] = $page->cID;

            }

            

            $newsArticles = new PageList();

            $newsArticles->filterByParentID($pageIds);

            $newsArticles->setItemsPerPage($articles);

            $newsArticles->sortBy('cvDatePublic', 'desc');

            return $newsArticles->getPage();

            

        }

        

        

        static function compare_date($a, $b) {

                if ($a['values']['date'] == $b['values']['date']) {

                    return 0;

                }

                return ($a['values']['date'] > $b['values']['date']) ? -1 : 1;

        

        }

	

}



?>