<?php

defined('C5_EXECUTE') or die("Access Denied.");

class GetNewsInfoHelper {

    public static function getNewsFromJson() {

        $jsonNews = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/files/widget/news.json');

        $news = json_decode($jsonNews);

        return $news;
    }

    /*
     * Applies rules of featuring and de-duplicates list
     */
    public static function buildFeaturedList ($district)
    {
        //how many district articles to include first
        $districtNumber = 4;

        //what other articles to include after the featured district
        $otherFeatures = array (
            'All Districts'
        );

        //how many of each of the other articles to include
        $otherNumber = 3;

        //build district list first
        $districtList = static::getRecentNews(2, $district);
        foreach ($districtList as $districtArticle) {
            $districtArticle->date = date(DATE_RSS);
            $articleList[$districtArticle->link] = $districtArticle;

        }

        $secondaryList = static::getRecentNews($otherNumber, $otherFeatures);
            foreach ($secondaryList as $secondaryArticle) {
                $articleList[$secondaryArticle->link] = $secondaryArticle;
            }
        return array_values($articleList);
    }


    /**
     *
     * @param int $articles - number of articles to retrieve
     * @param mixed $district = Can be either a string of the district or an array string district names
     * @return PageList of articles
     */
    public static function getRecentNews($articles = 50, $district = null) {

        Loader::model('page_list');
        Loader::model('article');

        $newsSection = new PageList();

        $newsSection->filterByNewsSection(1);

        $pageList = $newsSection->get();
        $pageIds = array();

        foreach ($pageList as $page) {
            $pageIds[] = $page->cID;
        }

        $newsArticles = new PageList();
        $newsArticles->filterByParentID($pageIds);
        if ($district) {
            $queryString = static::createQueryString($district);

            $newsArticles->filter(false, $queryString);
        }
        $newsArticles->filter(false, "ak_group_status like '%Published%'");

        $newsArticles->setItemsPerPage($articles);
        $newsArticles->sortBy('cvDatePublic', 'desc');
        $collectionList = $newsArticles->getPage();
        return article::buildFromPageList($collectionList);
    }

    /**
     * Loads district news using the json summary file if it is available in
     * order to drasticlly reduce the page load speed and the load on the server
     * of loading multiple article pages.
     *
     * @param string $district - The district news to retrieve
     * @param type $num - The number of recent articles to retrieve
     */
    public function getRecentDistrictNewsFromFile ($district, $num = 2, $exclude = '') {
        $news = static::getNewsFromJson();
        $count = 0;
        $articles = array();

        foreach ($news as $item) {
            if ($item->District == $district && $item->Headline != $exclude) {
                $count++;
                $articles[] = $item;
            }

            if ($count >= $num) {
                break;
            }
        }
        return $articles;
    }

    static function compare_date($a, $b) {

        if ($a['values']['date'] == $b['values']['date']) {
            return 0;
        }

        return ($a['values']['date'] > $b['values']['date']) ? -1 : 1;
    }

    public static function createQueryString($districts = null)
    {
        $n = 0;
        $queryString = '';
        if (is_array($districts)) {
            foreach ($districts as $district) {
                if ($n == 0) {
                    $queryString = "(ak_district like '%$district%'";
                } else {
                    $queryString = $queryString . " or ak_district like '%$district%'";
                }
                $n++;
            }
            $queryString = $queryString . ')';
            return $queryString;

        } else {
            return "(ak_district like '%$districts%')";
        }
    }

}
