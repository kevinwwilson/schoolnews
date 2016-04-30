<?php
class CustomSearchController extends Controller  {

   public function view() {
        $this->setDistrictList();

        $searchText = '';

        //if the specific text is provided, separated from the district name, then use that
        if (is_string($_GET['text'])) {
            $searchText = $_GET['text'];
        } elseif (is_string($_GET['q'])) {
            //otherwise use the same query string that was provided
            $searchText = $_GET['q'];
        }

        //get the currently seleected distrcit if there is one
        if (is_string($_GET['dist'])) {
            $dist = (int)$_GET['dist'];
        }

        $this->set('searchText', $searchText);
        $this->set('selectedDist', $dist);

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
