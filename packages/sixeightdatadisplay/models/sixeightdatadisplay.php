<?php    
	class sixeightdatadisplay extends Model {
		var $tID;
		
		const formsPagePath = '/dashboard/sixeightdatadisplay/forms';
		const formsArea = 'sixEight_formListArea';
		
		public function getTemplates($type='') {
			$db = Loader::db();
			if (($type == 'list') || ($type == 'detail')) {
				$templates = $db->getAll("SELECT tID, fID, templateName, templateType FROM sixeightdatadisplayTemplates WHERE templateType=? ORDER BY templateName ASC",array($type));
			} else {
				$templates = $db->getAll("SELECT tID, fID, templateName, templateType FROM sixeightdatadisplayTemplates ORDER BY templateName ASC");
			}	
			return $templates;
		}
		
		public function getTemplate($tID) {
			$db = Loader::db();
			$row = $db->getRow("SELECT tID, fID, templateType, templateName, templateHeader, templateContent, templateAlternateContent, templateFooter, templateEmpty FROM sixeightdatadisplayTemplates WHERE tID=?", array($tID));
			return $row;
		}
		
		public function createTemplate($templateAttributes,$templateType) {
			$db = Loader::db();
			if($templateType != 'detail') {
				$templateType = 'list';
			}
			$db->execute("INSERT INTO sixeightdatadisplayTemplates (tID, fID, templateName, templateType) VALUES (0, ?, ?,?)", array($templateAttributes['fID'], $templateAttributes['templateName'], $templateType));
			return $db->Insert_ID();
		}
		
		public function deleteTemplate($tID) {
			$db = Loader::db();
			if($db->execute("DELETE FROM sixeightdatadisplayTemplates WHERE tID=? LIMIT 1", array($tID))) {
				return true;
			} else {
				return false;
			}
		}
		
		public function saveTemplate($templateAttributes) {
			$db = Loader::db();
			if($db->execute("UPDATE sixeightdatadisplayTemplates SET templateName=?, fID=?, templateHeader=?, templateContent=?, templateAlternateContent=?, templateFooter=?, templateEmpty=? WHERE tID=?", array($templateAttributes['templateName'],$templateAttributes['fID'],$templateAttributes['templateHeader'],$templateAttributes['templateContent'],$templateAttributes['templateAlternateContent'],$templateAttributes['templateFooter'],$templateAttributes['templateEmpty'],$templateAttributes['tID']))) {
				return true;
			} else {
				return false;
			}
		}
		
		public function duplicateTemplate($tID,$newTemplateName) {
			$db = Loader::db();
			$t = Sixeightdatadisplay::getTemplate($tID);
			$attributes = array($t['fID'],$newTemplateName);
			$templateType = $t['templateType'];
			$newID = Sixeightdatadisplay::createTemplate($attributes,$templateType);
			if(Sixeightdatadisplay::saveTemplate(array('templateName' => $newTemplateName,'fID' => $t['fID'],'templateHeader' => $t['templateHeader'],'templateContent' => $t['templateContent'],'templateAlternateContent' => $t['templateAlternateContent'],'templateFooter' => $t['templateFooter'],'tID' => $newID))) {
				return true;
			} else {
				return false;
			}
		}

		
		public function getFormPlaceHolders($fID,$templateType) {
			Loader::model('form','sixeightforms');
			$db = Loader::db();
			$f = sixeightForm::getByID($fID);
			$fields = $f->getFields();
			
			$placeholders[] = array(title => '---Template Placeholders---', code => '');
			
			//Placeholder for Answer ID
			$placeholders[] = array(title => 'Record ID', code => '&lt;recordid /&gt;');
			
			//Placeholder for Owner Username
			$placeholders[] = array(title => 'Owner Username', code => '&lt;owner /&gt;');
			
			//Placeholder for Owner Email
			$placeholders[] = array(title => 'Owner Email', code => '&lt;owner attribute=&quot;email&quot; /&gt;');
			
			//Placeholder for Owner ID
			$placeholders[] = array(title => 'Owner ID', code => '&lt;owner attribute=&quot;id&quot; /&gt;');
			
			//Placeholder for Owner Attribute
			$placeholders[] = array(title => 'Owner Custom Attribute', code => '&lt;owner attribute=&quot;&quot; /&gt;');
			
			if($templateType=='list') {
				//Placeholder for Detail URL
				$placeholders[] = array(title => 'Detail URL', code => '{{DETAILURL}}');
			} else {
				//Placeholder for List URL
				$placeholders[] = array(title => 'List URL', code => '{{LISTURL}}');
			}
			
			//Placeholder for Current URL
			$placeholders[] = array(title => 'Current URL', code => '{{CURRENTURL}}');
			
			//Placeholder for Answer Timestamp
			$placeholders[] = array(title => 'Timestamp', code => '&lt;timestamp format=&quot;F j, Y&quot; /&gt;');
			
			//Placeholder for Edit Button
			$placeholders[] = array(title => 'Edit Link', code => '&lt;edit /&gt;');
			
			//Placeholder for Delete Button
			$placeholders[] = array(title => 'Delete Link', code => '&lt;delete /&gt;');
			
			//Placeholder for Approve/Unapprove Button
			$placeholders[] = array(title => 'Approve/Unapprove Link', code => '&lt;approve /&gt;');
			
			$placeholders[] = array(title => '---Form Fields Placeholders---', code => '');
			
			//Form Question Placeholders
			foreach($fields as $field) {
				if($field->type != 'Text (no user input)') {
					$placeholders[] = array(title => htmlspecialchars($field->shortLabel), code => htmlspecialchars('<field name="' . htmlspecialchars($field->label) . '" />'));
				}
			}
			
			return $placeholders;
		}
		
		public function getXML($tID) {
			$t = sixeightdatadisplay::getTemplate($tID);
			$templateXML = new SimpleXMLElement('<?php xml version="1.0" ?><template></template>');
			$templateXML->addAttribute('type',$t['templateType']);
			$templateXML->addAttribute('name',$t['templateName']);
			
			if($t['templateType'] == 'list') {
				$templateXML->addChild('header',$t['templateHeader']);
				$templateXML->addChild('content',$t['templateContent']);
				$templateXML->addChild('alternate',$t['templateAlternateContent']);
				$templateXML->addChild('footer',$t['templateFooter']);
				$templateXML->addChild('empty',$t['templateEmpty']);
			} else {
				$templateXML->addChild('content',$t['templateContent']);
				$templateXML->addChild('empty',$t['templateEmpty']);
			}
			
			return $templateXML; 
		}
	}
?>