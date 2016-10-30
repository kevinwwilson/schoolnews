<?php
class SeriesList extends Object
{
    public $title;
    public $link;
    public $summary;
    public $mainImage;

    /*
     * Takes a c5 page_list model and returns an array of article models
     */
    public static function buildFromPageList($pageList) {
        $seriesList = array();
        foreach ($pageList as $page) {
            $sl = new SeriesList();
            $sl->getArticleAttributes($page);
            $seriesList[] = $sl;
        }
        return $seriesList;
    }

    public function getArticleAttributes($page)
    {
        $nh = Loader::helper('navigation');
        Loader::model('page_list');

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
