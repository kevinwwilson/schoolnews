<?php
class article extends Object
{
    public $title;
    public $link;
    public $author;
    public $dateline;
    public $longSummary;
    public $summary;
    public $secondaryHeadline;
    public $photoType;
    public $mainImage;
    public $slideShow;

    public function getArticleAttributes($page)
    {
        $nh = Loader::helper('navigation');
        Loader::model('page_list');
        
        $this->title = $page->getCollectionName();
        $this->link = $nh->getLinkToCollection($page);
        $this->author = $page->getAttribute('author');
        $this->dateline = $page->getAttribute('dateline');

        $long_summary = CollectionAttributeKey::getByHandle('long_summary');
        $this->longSummary = $page->getCollectionAttributeValue($ak_long_summary);
        $this->summary = $page->getCollectionDescription();
        $this->secondaryHeadline = $page->getAttribute('secondary_headline');
        $this->photoType = $page->getAttribute('single_multiple_photo_status');

        $this->mainImage = $this->getMainImage($page);
        $this->slideShow = $this->getSlideImages($page);
    }
    
    public function getMainImage($page) {

        $photoType = $page->getAttribute('single_multiple_photo_status');
        if ($photoType == 1) {
            //this is a single photo with a main photo assigned
            $image = $page->getAttribute('main_photo');
        } elseif ($photoType == 2) {
            //this is a slideshow 
            $slideimage = $page->getAttribute('files');
            $sliderimages = explode('^', $slideimage);

            //Get the first photo in the slideshow
            $sliders = explode('||', $sliderimages[0]);
            $image = File::getByID($sliders[0]);
        }
        return $image;
    }

    public function getSlideImages($page) {
        $photoType = $page->getAttribute('single_multiple_photo_status');
        
        if ($photoType == 2) {
            $slideimage = $page->getAttribute('files');
            $sliderimages = explode('^', $slideimage);

            $fileList = array();
            foreach ($sliderimages as $simages) {
                $sliders = explode('||', $simages);
                $fileList[] = File::getByID($sliders[0]);
            }
            return $fileList;
        } else {
           return null;
        }
    }
    
    public function resizeMainImage($width, $height)
    {
        $ih = Loader::helper('image');
        $thumb = $ih->getThumbnail($this->mainImage, $width, $height);
        return $thumb->src;
    }
}
