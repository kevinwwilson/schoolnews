<?php
class DistrictPagesHelper{
    
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
        if (array_key_exists($district, $map)) {
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
                'link'  => '/districts/byron-center',
                'image' => 'bcps.jpg',
                'abbrev'=> 'bcps'
                ),
            'Caledonia Community Schools'       =>array (
                'link' => '/districts/caledonia',
                'image' => 'ccs.jpg',
                'abbrev'=> 'ccs'
                ),
            'Cedar Springs Public Schools'      =>array (
                'link' => '/districts/cedar-springs',
                'image' => 'csps.jpg',
                'abbrev'=> 'csps'
                ),
            'Comstock Park Public Schools'      =>array (
               'link' => '/districts/comstock-park',
                'image' => 'cpps.jpg',
                'abbrev'=> 'cpps'
                ),
            'East Grand Rapids Public Schools'  =>array (
                'link' => '/districts/east-grand-rapids',
                'image' => 'egr.jpg',
                'abbrev'=> 'egr'
                ),
            'Forest Hills Public Schools'       =>array (
                'link' => '/districts/forest-hills',
                'image' => 'fhps.jpg',
                'abbrev'=> 'fhps'
                ),
            'Godwin Heights Public Schools'     =>array (
                'link' => '/districts/godwin-heights',
                'image' => '',
                'abbrev'=> 'ghps'
                ),
            'Godfrey Lee Public Schools'        =>array (
                'link' => '/districts/godfrey-lee',
                'image' => 'glps.jpg',
                'abbrev'=> 'glps'
                ),
            'Grand Rapids Public Schools - GRPS'=>array (
                'link' => '/districts/grand-rapids',
                'image' => 'grps.jpg',
                'abbrev'=> 'grps'
                ),
            'Grandville Public Schools'         =>array (
                'link' => '',
                'image' => '',
                'abbrev'=> 'gps'
                ),
            'Kelloggsville Public Schools'      =>array (
                'link' => '/districts/kelloggsville',
                'image' => 'ks.jpg',
                'abbrev'=> 'ks'
                ),
            'Kenowa Hills Public Schools'       =>array (
                'link' => '/districts/kenowa-hills',
                'image' => 'khps.jpg',
                'abbrev'=> 'khps'
                ),
            'Kent ISD'                          =>array (
                'link' => '/districts/kent-isd',
                'image' => 'kisd.png',
                'abbrev'=> 'kisd'
                ),
            'Kent City Community Schools'       =>array (
                'link' => '/districts/kent-city',
                'image' => 'kccs.jpg',
                'abbrev'=> 'kccs'
                ),
            'Kentwood Public Schools'           =>array (
                'link' => '/districts/kentwood',
                'image' => 'kps.jpg',
                'abbrev'=> 'kps'
                ),
            'Lowell Area Schools'               =>array (
                'link' => '/districts/Lowell',
                'image' => 'las.jpg',
                'abbrev'=> 'las'
                ),
            'Northview Public Schools'          =>array (
                'link' => '/districts/northview',
                'image' => 'nps.jpg',
                'abbrev'=> 'nps'
                ),
            'Rockford Public Schools'           =>array (
                'link' => '/districts/rockford',
                'image' => 'rps.jpg',
                'abbrev'=> 'rps'
                ),
            'Sparta Area Schools'               =>array (
                'link' => '/districts/sparta',
                'image' => 'sas.jpg',
                'abbrev'=> 'sas'
                ),
            'Thornapple Kellogg Schools'        =>array (
                'link' => '/districts/thornapple-kellogg',
                'image' => 'tks.jpg',
                'abbrev'=> 'tks'
                ),
            'Wyoming Public Schools'            =>array (
                'link' => '/districts/wyoming',
                'image' => 'wps.jpg',
                'abbrev'=> 'wps'
                ),
            'Catholic Diocese of Grand Rapids'  =>array (
                'link' => '',
                'image' => '',
                'abbrev'=> 'cdgr'
                ),
            'Grand Rapids Christian Schools'    =>array (
                'link' => '',
                'image' => '',
                'abbrev'=> 'grcs'
                ),
            'Calvin Christian Schools'          =>array (
                'link' => '',
                'image' => '',
                'abbrev'=> 'cc'
                ),
            );    
    }
    
    
}