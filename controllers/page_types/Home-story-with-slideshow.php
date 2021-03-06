<?php
class HomeStoryWithSlideshowPageTypeController extends Controller
{
    public function view() {
        $this->set('northThumbnail', $this->getRegionalThumbnail('North Thumb'));
        $this->set('SWThumbnail', $this->getRegionalThumbnail('SW Thumb'));
        $this->set('SEThumbnail', $this->getRegionalThumbnail('SE Thumb'));
        $this->set('centralThumbnail', $this->getRegionalThumbnail('Central Thumb'));

        //add the series list
        $header = '<script type="text/javascript" src="/js/cycle/cycle.min.js"></script>';
        $this->addHeaderItem($header);
        $newsHelper = Loader::helper("news_loader");
        $seriesList = $newsHelper->getSeriesList();
        $this->set('seriesList', $seriesList);
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
