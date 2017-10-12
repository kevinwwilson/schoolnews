<?php

class DistrictNewsPageTypeController extends Controller{
    public function view()
    {
        $header = '<script type="text/javascript" src="/js/cycle/cycle.min.js"></script>';
        $this->addHeaderItem($header);

        $newsHelper = Loader::helper("news_loader");
        $newsList = $newsHelper->getHomeNews();
        $this->set('newsList', $newsList);

        $seriesList = $newsHelper->getSeriesList();
        $this->set('seriesList', $seriesList);
    }
}
