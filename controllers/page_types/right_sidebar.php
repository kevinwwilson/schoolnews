<?php

class RightSidebarPageTypeController extends Controller{
    public function view()
    {
        $photoType = $this->c->getAttribute('single_multiple_photo_status');
        if ($this->c->getAttribute('main_photo') != '' && $photoType == 1) {
            $imageFile = $this->c->getAttribute('main_photo');
            $imagePath = $imageFile->getURL();

        } elseif ($photoType == 2) {
            $slideshowFiles = $this->c->getAttribute('files');
            $slideList = explode('^', $slideshowFiles);
            $firstImage = explode('||', $slideList[0]);
            $imageFile = File::getByID($firstImage[0]);
            $imagePath = $imageFile->getURL();
        }
        //header items added via controller instead of directly to element
        //so that they don't slow down all pages like the home page where they are
        //not necessary.

        //add Facebook header items 
        $openGraph = '<meta property="og:image" content="' . $imagePath . '"/>';
        $this->addHeaderItem($openGraph);
        
        //add share this header script
        $shareButtonScript = '<script type="text/javascript">var switchTo5x=true;</script>';
        $shareButtonScript .= '<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>';
        $shareButtonScript .= '<script type="text/javascript">stLight.options({publisher: "7f8dd855-a413-4e5c-85b0-9e266c6a116d", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>';
        $this->addHeaderItem($shareButtonScript);
    }
}
