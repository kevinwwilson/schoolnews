<?php
class CustomSearchController extends Controller  {

   public function view() {
        $this->setDistrictList();
    }

    private function setDistrictList()
    {
      Loader::model("attribute/categories/collection");
      $atCategory = AttributeKeyCategory::getByHandle('collection');
      $atKey = $atCategory->getAttributeKeyByHandle('district');
      $satc = new SelectAttributeTypeController(AttributeType::getByHandle('select'));
      $satc->setAttributeKey($atKey);
      $list = $satc->getOptions();
      $this->set('districtList',$list);
    }

}
