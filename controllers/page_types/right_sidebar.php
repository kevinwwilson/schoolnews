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
        
        $openGraph = '<meta property="og:image" content="' . $imagePath . '"/>';
        $this->addHeaderItem($openGraph);
    }
}
