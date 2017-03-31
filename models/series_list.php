<?php
class SeriesList extends Object
{
    public $id;
    public $title;
    public $link;
    public $summary;
    public $mainImage;

    /*
    * If trying to convert this object to a string, use the page title.
    */
    public function __toString() {
        return $this->title;
    }

    /*
     * Takes a c5 page_list model and returns an array of article models
     */
    public static function buildFromPageList($pageList) {
        $seriesList = array();
        if (count($pageList) > 0) {
            foreach ($pageList as $page) {
                $sl = new SeriesList();
                $sl->getArticleAttributes($page);
                $seriesList[$sl->id] = $sl;
            }
        }
        return $seriesList;
    }

    public static function getById($pageId) {
        $seriesPage = Page::getByID($pageId);
        $sl = new SeriesList();
        $sl->getArticleAttributes($seriesPage);
        return $sl;
    }

    public function getArticleAttributes($page)
    {
        $nh = Loader::helper('navigation');
        Loader::model('page_list');

        $this->id = $page->getCollectionID();
        $this->title = $page->getCollectionName();
        $this->link = BASE_URL.DIR_REL . $nh->getLinkToCollection($page);
        $this->summary = $page->getCollectionDescription();
        $this->mainImage = $page->getAttribute('main_photo');
    }

    public function resizeMainImage($width, $height)
    {
        $ih = Loader::helper('image');
        $thumb = $ih->getThumbnail($this->mainImage, $width, $height);
        return $thumb->src;
    }
}
