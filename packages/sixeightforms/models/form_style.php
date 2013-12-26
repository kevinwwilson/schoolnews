<?php  

	class sixeightformstyle extends Object {
	
		var $sID;
		var $name;
		var $selectors = array();
		
		public function getAll() {
			$db = Loader::db();
			$styleIDs = $db->execute("SELECT sID FROM sixeightformsStyles ORDER BY name");
			$styles = array();
			foreach($styleIDs as $sID) {
				$styles[] = sixeightformstyle::getByID($sID);
			}
			return $styles;
		}
	
		public function create($name) {
			$db = Loader::db();
			$db->execute("INSERT INTO sixeightformsStyles (sID, name) VALUES (0,?)",array($name));
			return sixeightformstyle::getByID($db->Insert_ID());
		}
		
		public function delete($sID) {
			$db = Loader::db();
			$db->execute("DELETE FROM sixeightformsStyles WHERE sID=?",array($sID));
			$db->execute("DELETE FROM sixeightformsStylesSelectors WHERE sID=?",array($sID));
		}

		public function getByID($sID) {
			$db = Loader::db();
			$styleRow = $db->getRow("SELECT * FROM sixeightformsStyles WHERE sID=?",array($sID));

			$style = new sixeightformstyle;
			$style->sID = $styleRow['sID'];
			$style->name = $styleRow['name'];
			$style->getSelectors();	
			return $style;
		}
		
		public function getByName($name) {
			$db = Loader::db();
			$styleRow = $db->getRow("SELECT * FROM sixeightformsStyles WHERE name=?",array($name));

			$style = new sixeightformstyle;
			$style->sID = $styleRow['sID'];
			$style->name = $styleRow['name'];
			$style->getSelectors();	
			return $style;
		}
		
		public function getSelectors() {
			$db = Loader::db();
			$selectors = $db->getAll("SELECT * FROM sixeightformsStylesSelectors WHERE sID=? ORDER BY ssID",array($this->sID));
			$this->selectors = $selectors;
		}
		
		public function setSelector($name,$css='',$description='',$isRequired=false) {
			$db = Loader::db();
			if($isRequired) {
				$required = 1;
			} else {
				$required = 0;
			}
			$db->execute("DELETE FROM sixeightformsStylesSelectors WHERE sID=? AND name=?",array($this->sID,$name));
			$db->execute("INSERT INTO sixeightformsStylesSelectors (ssID,sID,name,css,description,required) VALUES (0,?,?,?,?,?)",array($this->sID,$name,$css,$description,$required));
			$this->getSelectors();
		}
		
		public function setSelectorByID($ssID,$css) {
			$db = Loader::db();
			$db->execute("UPDATE sixeightformsStylesSelectors SET css=? WHERE ssID=?",array($css,$ssID));
		}
		
		public function deleteSelectorByID($ssID) {
			$db = Loader::db();
			$db->execute("DELETE FROM sixeightformsStylesSelectors WHERE ssID=?",array($ssID));
		}
		
		public function deleteSelectorByName($name) {
			$db = Loader::db();
			$db->execute("DELETE FROM sixeightformsStylesSelectors WHERE sID=? AND name=?",array($this->sID,$name));
		}
		
		public function duplicate() {
			$newStyle = sixeightformStyle::create($this->name . ' Copy');
			foreach($this->selectors as $selector) {
				$newStyle->setSelector($selector['name'],$selector['css'],$selector['description'],$selector['required']);
			}
			return $newStyle;
		}
		
		public function render() {
			foreach($this->selectors as $selector) {
				if(($selector['name'] != '') && ($selector['css'] != '')) {
					echo $selector['name'] . " {\n";
					echo $selector['css'] . "\n}\n\n";
				} elseif ($selector['css'] != '') {
					echo $selector['css'] . "\n\n";
				}
			}
		}
		
		public function getXML() {
			$styleXML = new SimpleXMLElement('<?php xml version="1.0" ?><style></style>');
			$styleXML->addAttribute('name',$this->name);
			
			if(count($this->selectors) > 0) {
				foreach($this->selectors as $selector) {
					$selectorXML = $styleXML->addChild('selector');
					$selectorXML->addAttribute('css',$selector['css']);
					$selectorXML->addAttribute('name',$selector['name']);
					$selectorXML->addAttribute('description',$selector['description']);
				}
			}
			
			return $styleXML; 
		}
	}

?>