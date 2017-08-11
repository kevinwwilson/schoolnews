<?php
class DistrictPagesHelper{

  public function getDistrictTitle($district)
  {
      $map = $this->getDistrictMap();
      if (array_key_exists($district, $map)) {
          return $map[$district]['title'];
      } else {
          return null;
      }

  }

    public function getDistrictLink($district)
    {
        $map = $this->getDistrictMap();
        if (array_key_exists($district, $map)) {
            return $map[$district]['link'];
        } else {
            return null;
        }

    }

    public function getDistrictImage($district)
    {
        $districtLogoPath = '/logo/district/';

        $map = $this->getDistrictMap();
        if (array_key_exists($district, $map) && strlen($map[$district]['image']) > 0) {
            return $districtLogoPath . $map[$district]['image'];
        } else {
            return null;
        }

    }

    public function getByAbbrev($abbrev)
    {
        $map = $this->getDistrictMap();
        foreach ($map as $districtName => $districtInfo) {
            if ($districtInfo['abbrev'] == $abbrev) {
                return $districtName;
            }
        }
        return null;
    }


    private function getDistrictMap()
    {
        return array(
            'Byron Center Public Schools'       =>array (
                'title' => 'Byron Center<br/>Public Schools',
                'link'  => '/districts/byron-center',
                'image' => 'bcps.jpg',
                'abbrev'=> 'bcps'
                ),
            'Caledonia Community Schools'       =>array (
                'title' => 'Caledonia<br>Community Schools',
                'link' => '/districts/caledonia',
                'image' => 'ccs.jpg',
                'abbrev'=> 'ccs'
                ),
            'Cedar Springs Public Schools'      =>array (
                'title' => 'Cedar Springs<br/>Public Schools',
                'link' => '/districts/cedar-springs',
                'image' => 'csps.jpg',
                'abbrev'=> 'csps'
                ),
            'Comstock Park Public Schools'      =>array (
                'title' => 'Comstock Park<br/>Public Schools',
                'link' => '/districts/comstock-park',
                'image' => 'cpps.jpg',
                'abbrev'=> 'cpps'
                ),
            'East Grand Rapids Public Schools'  =>array (
                'title' => 'East Grand Rapids<br/>Public Schools',
                'link' => '/districts/east-grand-rapids',
                'image' => 'egr.jpg',
                'abbrev'=> 'egr'
                ),
            'Forest Hills Public Schools'       =>array (
                'title' => 'Forest Hills<br/>Public Schools',
                'link' => '/districts/forest-hills',
                'image' => 'fhps.gif',
                'abbrev'=> 'fhps'
                ),
            'Godwin Heights Public Schools'     =>array (
                'title' => 'Godwin Heights<br/>Public Schools',
                'link' => '/districts/godwin-heights',
                'image' => 'ghps.jpg',
                'abbrev'=> 'ghps'
                ),
            'Godfrey Lee Public Schools'        =>array (
                'title' => 'Godfrey-Lee<br/>Public Schools',
                'link' => '/districts/godfrey-lee',
                'image' => 'glps.jpg',
                'abbrev'=> 'glps'
                ),
            'Grand Rapids Public Schools - GRPS'=>array (
                'title' => 'Grand Rapids<br/>Public Schools',
                'link' => '/districts/grand-rapids',
                'image' => 'grps.jpg',
                'abbrev'=> 'grps'
                ),
            'Grandville Public Schools'         =>array (
                'title' => 'Grandville<br/>Public Schools',
                'link' => '/districts/grandville',
                'image' => 'gps.jpg',
                'abbrev'=> 'gps'
                ),
            'Kelloggsville Public Schools'      =>array (
                'title' => 'Kelloggsville<br/>Public Schools',
                'link' => '/districts/kelloggsville',
                'image' => 'kvps.jpg',
                'abbrev'=> 'kvps'
                ),
            'Kenowa Hills Public Schools'       =>array (
                'title' => 'Kenowa Hills<br/>Public Schools',
                'link' => '/districts/kenowa-hills',
                'image' => 'khps.jpg',
                'abbrev'=> 'khps'
                ),
            'Kent ISD'                          =>array (
                'title' => 'Kent ISD',
                'link' => '/districts/kent-isd',
                'image' => '',
                'abbrev'=> 'kisd'
                ),
            'Kent City Community Schools'       =>array (
                'title' => 'Kent City<br/>Public Schools',
                'link' => '/districts/kent-city',
                'image' => 'kccs.jpg',
                'abbrev'=> 'kccs'
                ),
            'Kentwood Public Schools'           =>array (
                'title' => 'Kentwood<br/>Public Schools',
                'title' =>  'Kentwood<br/>Public Schools',
                'link' => '/districts/kentwood',
                'image' => 'kps.jpg',
                'abbrev'=> 'kps'
                ),
            'Lowell Area Schools'               =>array (
                'title' => 'Lowell<br/>Area Schools',
                'link' => '/districts/Lowell',
                'image' => 'las.jpg',
                'abbrev'=> 'las'
                ),
            'Northview Public Schools'          =>array (
                'title' => 'Northview<br/>Public Schools',
                'link' => '/districts/northview',
                'image' => 'nps.jpg',
                'abbrev'=> 'nps'
                ),
            'Rockford Public Schools'           =>array (
                'title' => 'Rockford<br/>Public Schools',
                'link' => '/districts/rockford',
                'image' => 'rps.jpg',
                'abbrev'=> 'rps'
                ),
            'Sparta Area Schools'               =>array (
                'title' => 'Sparta<br/>Area Schools',
                'link' => '/districts/sparta',
                'image' => 'sas.jpg',
                'abbrev'=> 'sas'
                ),
            'Thornapple Kellogg Schools'        =>array (
                'title' => 'Thornapple Kellogg<br/>Schools',
                'link' => '/districts/thornapple-kellogg',
                'image' => 'tks.jpg',
                'abbrev'=> 'tks'
                ),
            'Wyoming Public Schools'            =>array (
                'title' => 'Wyoming<br/>Public Schools',
                'link' => '/districts/wyoming',
                'image' => 'wps.jpg',
                'abbrev'=> 'wps'
            ),
            'All Districts'            =>array (
                'title' => 'Kent County<br/>Area Schools',
                'link' => '/series',
                'image' => 'alldist.png',
                'abbrev'=> 'all'
                )
            );
    }


}
