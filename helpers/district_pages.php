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
        $map = $this->getDistrictMap();
        if (array_key_exists($district, $map)) {
            return $map[$district]['link'];
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
                'image' => '',
                'abbrev'=> 'bcps'
                ),
            'Caledonia Community Schools'       =>array (
                'link' => '/districts/caledonia',
                'image' => '',
                'abbrev'=> 'ccs'
                ),
            'Cedar Springs Public Schools'      =>array (
                'link' => '/districts/cedar-springs',
                'image' => '',
                'abbrev'=> 'csps'
                ),
            'Comstock Park Public Schools'      =>array (
               'link' => '/districts/comstock-park',
                'image' => '',
                'abbrev'=> 'cpps'
                ),
            'East Grand Rapids Public Schools'  =>array (
                'link' => '/districts/east-grand-rapids',
                'image' => '',
                'abbrev'=> 'egr'
                ),
            'Forest Hills Public Schools'       =>array (
                'link' => '/districts/forest-hills',
                'image' => '',
                'abbrev'=> 'fhps'
                ),
            'Godwin Heights Public Schools'     =>array (
                'link' => '/districts/godwin-heights',
                'image' => '',
                'abbrev'=> 'ghps'
                ),
            'Godfrey Lee Public Schools'        =>array (
                'link' => '/districts/godfrey-lee',
                'image' => '',
                'abbrev'=> 'glps'
                ),
            'Grand Rapids Public Schools - GRPS'=>array (
                'link' => '/districts/grand-rapids',
                'image' => '',
                'abbrev'=> 'grps'
                ),
            'Grandville Public Schools'         =>array (
                'link' => '',
                'image' => '',
                'abbrev'=> 'gps'
                ),
            'Kelloggsville Public Schools'      =>array (
                'link' => '/districts/kelloggsville',
                'image' => '',
                'abbrev'=> 'kps'
                ),
            'Kenowa Hills Public Schools'       =>array (
                'link' => '/districts/kenowa-hills',
                'image' => '',
                'abbrev'=> 'khps'
                ),
            'Kent ISD'                          =>array (
                'link' => '/districts/kent-isd',
                'image' => '',
                'abbrev'=> 'kisd'
                ),
            'Kent City Community Schools'       =>array (
                'link' => '/districts/kent-city',
                'image' => '',
                'abbrev'=> 'kccs'
                ),
            'Kentwood Public Schools'           =>array (
                'link' => '/districts/kentwood',
                'image' => '',
                'abbrev'=> 'kps'
                ),
            'Lowell Area Schools'               =>array (
                'link' => '/districts/Lowell',
                'image' => '',
                'abbrev'=> 'las'
                ),
            'Northview Public Schools'          =>array (
                'link' => '/districts/northview',
                'image' => '',
                'abbrev'=> 'nps'
                ),
            'Rockford Public Schools'           =>array (
                'link' => '/districts/rockford',
                'image' => '',
                'abbrev'=> 'rps'
                ),
            'Sparta Area Schools'               =>array (
                'link' => '/districts/sparta',
                'image' => '',
                'abbrev'=> 'sas'
                ),
            'Thornapple Kellogg Schools'        =>array (
                'link' => '/districts/thornapple-kellogg',
                'image' => '',
                'abbrev'=> 'tks'
                ),
            'Wyoming Public Schools'            =>array (
                'link' => '/districts/wyoming',
                'image' => '',
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
                'abbrev'=> ''
                ),
            );    
    }
    
    
}