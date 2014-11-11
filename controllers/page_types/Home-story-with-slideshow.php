<?php
class HomeStoryWithSlideshowPageTypeController extends Controller
{
    public function view() {
        $this->set('northThumbnail', $this->getRegionalThumbnail('North Thumb'));
        $this->set('SWThumbnail', $this->getRegionalThumbnail('SW Thumb'));
        $this->set('SEThumbnail', $this->getRegionalThumbnail('SE Thumb'));
        $this->set('centralThumbnail', $this->getRegionalThumbnail('Central Thumb'));
    }
    
    public function getRegionalThumbnail($region)
    {
        Loader::model('file_list');
        $ih= Loader::helper('image');
        $fl = new FileList();
        $fs = FileSet::getByName($region);
        $fl->filterBySet($fs);
        $files = $fl->get();
        shuffle($files);
        $thumb= $ih->getThumbnail($files[0], 166, 117);
        $image = '<img alt="" src="'.$thumb->src.'">';	
        return $image;
    }
}
