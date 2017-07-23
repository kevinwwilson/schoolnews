<?php
class article extends Object implements JsonSerializable
{
    public $id;
    public $title;
    public $slug;
    public $date;
    public $status;
    public $district;
    public $link;
    public $author;
    public $dateline;
    public $longSummary;
    public $summary;
    public $secondaryHeadline;
    public $photoType;
    public $mainImage;
    public $mainImageUrl;
    public $mainImageCaption;
    public $slideShow;
    public $slideShowInfo;
    public $thumbnail;
    public $tags;
    public $series;
    public $content;

    public function jsonSerialize() {
        return array(
            'Id'    => $this->id,
            'Title' => $this->title,
            'Slug'  => $this->slug,
            'SecondaryHeadline' => $this->secondaryHeadline,
            'Status' => $this->status,
            'District' => $this->district,
            'Date' => $this->date,
            'URL' => $this->link,
            'Author'=> $this->author,
            'Dateline' => $this->dateline,
            'LongSummary' => $this->longSummary,
            'Summary' 	=> $this->summary,
            'PhotoType' => $this->photoType,
            'MainImage' => $this->mainImage,
            'MainImageURL' => $this->mainImageUrl,
            'MainImageCaption' => $this->mainImageCaption,
            'SlideShow' => $this->slideShow,
            'SlideShowInfo' => $this->slideShowInfo,
            'Thumbnail' => $this->thumbnail,
            'Tags'      => $this->tags,
            'Series'    =>  $this->series,
            'Content'	=> $this->content
        );
    }
    /*
     * Takes a c5 page_list model and returns an array of article models
     */
    public static function buildFromPageList($pageList) {
        $articleList = array();
        foreach ($pageList as $page) {
            $article = new article();
            $article->getArticleAttributes($page);
            $articleList[] = $article;
        }
        return $articleList;
    }

    public function getArticleAttributes($page)
    {
        $nh = Loader::helper('navigation');
        Loader::model('page_list');

        $this->id = $page->getCollectionID();
        $this->title = $page->getCollectionName();
        $this->slug = $page->getAttribute('story_slug');
        $this->date = $page->getCollectionDatePublic();
        $this->status = $page->getCollectionAttributeValue('group_status')->getOptions();
        $this->status = $this->status[0]->value;
        $this->district = $this->getDistricts($page);
        $this->link = BASE_URL.DIR_REL . $nh->getLinkToCollection($page);
        $this->author = $page->getAttribute('author');
        $this->dateline = $page->getAttribute('dateline')->getOptions();
        $this->dateline = $this->dateline[0]->value;
        $long_summary = CollectionAttributeKey::getByHandle('long_summary');
        $this->longSummary = $page->getCollectionAttributeValue($long_summary);
        $this->summary = $page->getCollectionDescription();
        $this->secondaryHeadline = $page->getAttribute('secondary_headline');
        $this->photoType = $page->getAttribute('single_multiple_photo_status');
        $this->thumbnail = $this->getThumbnailPath($page);
        $this->mainImage = $this->getMainImage($page);
        $this->mainImageUrl = $this->getMainImageUrl();
        $this->mainImageCaption = $this->getMainImageCaption($page);
        $this->slideShow = $this->getSlideImages($page);
        $this->slideShowInfo = $this->getSlideShowInfo($page);
        $this->tags = $this->getTags($page);
        $this->series = $page->getAttribute('series_index_id');
        $this->content = $this->getContent($page);
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

    public function getMainImageCaption($page) {
        $photoType = $page->getAttribute('single_multiple_photo_status');
        if ($photoType == 1) {
            return $page->getAttribute('photo_caption');
        } elseif ($photoType == 2) {
            //this is a slideshow
            $slideimage = $page->getAttribute('files');
            $sliderimages = explode('^', $slideimage);

            //Get the first photo in the slideshow
            $sliders = explode('||', $sliderimages[0]);
            return htmlspecialchars_decode($sliders[1]);
        } else {
            return null;
        }
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

    public function getThumbnailPath($page) {
        $thumbnail = $page->getCollectionAttributeValue('thumbnail');
        if($thumbnail != ''){
        $thumbnailPath = $thumbnail->getRelativePath();
        $fullThumbPath = BASE_URL.DIR_REL.''.$thumbnailPath;
        } else {

               $fullThumbPath = '';
        }
        return $fullThumbPath;
    }

    public function getContent($page) {
        $block = $page->getBlocks('Main');
        foreach ($block as $bi) {
            if ($bi->getBlockTypeHandle() == 'content') {
                $content = $bi->getInstance()->getContent();
            }
        }
        return $content;
    }

    public function getDistricts($page) {
        $district = $page->getAttribute('district');
        foreach ($district as $d) {
            $districtArr[] = $d->value;
        }
        return $districtArr;
    }

    public function getTags($page) {
        $tags = $page->getAttribute('news_tag');
        foreach ($tags as $tag) {
            $tagList[] = $tag->value;
        }
        return $tagList;
    }

    public function getMainImageUrl() {
        if (is_object($this->mainImage)) {
            return BASE_URL.DIR_REL.''.$this->mainImage->getRelativePath();
        } else {
            return null;
        }
    }

    public function getSlideShow() {
        if (is_array($this->slideShow)) {
            $urls = array();
            foreach ($this->slideShow as $file) {
                $urls[] = array(
                    'Id' => $file->getFileID(),
                    'Url' => BASE_URL.DIR_REL.''.$file->getRelativePath()
                );
            }
            return $urls;
        } else {
            return null;
        }
    }

    public function getSlideShowInfo($page) {
        if ($page->getAttribute('single_multiple_photo_status') == 2) {
            $slideimage = $page->getAttribute('files');
            $sliderimages = explode('^', $slideimage);

            $fileList = array();
            foreach ($sliderimages as $simages) {
                $sliders = explode('||', $simages);
                $file = File::getByID($sliders[0]);
                $captionList[] = array(
                        'Id' => $sliders[0],
                        'URL' => BASE_URL.DIR_REL.''.$file->getRelativePath(),
                        'Caption' => htmlspecialchars_decode($sliders[1])
                );
            }
            return $captionList;

        } else {
            return null;
        }
    }


}
