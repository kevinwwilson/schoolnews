<?php     

defined('C5_EXECUTE') or die(_("Access Denied."));
Loader::model('sixeightdatadisplay','sixeightdatadisplay');
Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('form_style','sixeightforms');

class DashboardSixeightdatadisplayToolsController extends Controller {
	
	function view() {
		$c=$this->getCollectionObject();
		$this->set('listTemplates',Sixeightdatadisplay::getTemplates('list'));
		$this->set('detailTemplates',Sixeightdatadisplay::getTemplates('detail'));
		$this->set('forms',sixeightForm::getAll());
		$this->set('styles',sixeightformstyle::getAll());
		
		if(Package::getByHandle('datadisplay')) {
			Loader::model('datadisplay','datadisplay');
			$this->set('oldListTemplates', DataDisplay::getTemplates('list'));
			$this->set('oldDetailTemplates', DataDisplay::getTemplates('detail'));
		}
	}
	
	public function createTemplate() {
		$id = Sixeightdatadisplay::createTemplate($_GET,'list');
		$this->redirect('/dashboard/datadisplay/list/-/getTemplate?tID=' . $id . '&new=true');
	}
	
	public function importFormData() {
		Loader::library('file/importer');
		$fi = new FileImporter();
		$resp = $fi->import($_FILES['data_file']['tmp_name'], $_FILES['data_file']['name'], $fr);

		if (!($resp instanceof FileVersion)) {
			switch($resp) {
				case FileImporter::E_FILE_INVALID_EXTENSION:
					$this->set('error', array(t('Invalid file extension.')));
					break;
				case FileImporter::E_FILE_INVALID:
					$this->set('error', array(t('Invalid file.')));
					break;
			}
		} else {
			$resp->getFileID();
			$filepath=$resp->getPath();  
			if (($handle = fopen($filepath, "r")) !== FALSE) {
			    while (($data = fgetcsv($handle)) !== FALSE) {
			        $num = count($data);
			        echo "<p> $num fields in line $row: <br /></p>\n";
			        $row++;
			        for ($c=0; $c < $num; $c++) {
			            echo $data[$c] . "<br />\n";
			        }
			    }
			    fclose($handle);
			}
		}
	}
	
	public function importData() {
		Loader::library('file/importer');
		$fi = new FileImporter();
		$resp = $fi->import($_FILES['data_file']['tmp_name'], $_FILES['data_file']['name'], $fr);

		if (!($resp instanceof FileVersion)) {
			switch($resp) {
				case FileImporter::E_FILE_INVALID_EXTENSION:
					$this->set('error', array(t('Invalid file extension.')));
					break;
				case FileImporter::E_FILE_INVALID:
					$this->set('error', array(t('Invalid file.')));
					break;
			}
		} else {
			$resp->getFileID();
			$filepath=$resp->getPath();  
			$filecontents = @file_get_contents($filepath);
			$xml = @simplexml_load_string($filecontents, 'SimpleXMLElement', LIBXML_NOCDATA);
			if(!$xml) {
				//Process errors
				$this->set('error', array(t('The file you uploaded does not appear to contain valid XML.')));
			} else {
				//Process the XML file
				$formErrors = 0;
				$totalImportErrors = 0;
				foreach($xml->forms->children() as $formXML) {
					$invalidForm = 0;
					$options = '';
					
					$data = array();
					$data['name'] = (string)$formXML['name'];
					
					foreach($formXML->children() as $property) {
						if($property->getName() == 'property') {
							$data[(string)$property['name']] = (string)$property['value'];
						}
					}
					
					if($data['name'] != '') {
					
						if($data['sendMail'] == 1) {
							$ui = UserInfo::getByID(1);							
							$data['mailTo'] = $ui->getUserEmail();
							$data['mailFrom'] = SITE;
							
							if($data['mailSubject'] == '') {
								$data['mailSubject'] = SITE . t(' Form Submission');
							}
						}
						
						$data['afterSubmit'] = 'thankyou';
						if($data['thankyouMsg'] == '') {
							$data['thankyouMsg'] = t('Thank you.');
						}
					
						$f = sixeightform::create($data);
						foreach($formXML->children() as $property) {
							if($property->getName() == 'fields') {
								foreach($property->children() as $field) {
									$fieldData = array();
									$fieldData['label'] = (string)$field['label'];
									
									foreach($field->children() as $fieldProperty) {
										if($fieldProperty->getName() == 'property') {
											$fieldData[(string)$fieldProperty['name']] = (string)$fieldProperty['value'];
										}
										
										if($fieldProperty->getName() == 'options') {
											$options = array();
											foreach($fieldProperty->children() as $option) {
												$options[] = $option['value'];
											}
										}
									}
									
									$f->addField($fieldData,$options);
								}
							}
						}
					} else {
						$this->set('error', array(t('One or more forms in the XML file could not imported.  Please confirm that all forms have a name')));
					}
				}
				
				//Import styles
				foreach($xml->styles->children() as $styleXML) {
					$style = sixeightformstyle::create((string)$styleXML['name']);
					foreach($styleXML->children() as $selector) {
						$name = (string)$selector['name'];
						$css = (string)$selector['css'];
						$description = (string)$selector['description'];
						$style->setSelector($name,$css,$description);
					}
				}
				
				//Import templates
				foreach($xml->templates->children() as $templateXML) {
					$templateAttributes = array();
					$templateAttributes['fID'] = 0;
					$templateAttributes['templateName'] = (string)$templateXML['name'];
					$templateType = (string)$templateXML['type'];
					
					$tID = sixeightdatadisplay::createTemplate($templateAttributes,$templateType);
					
					$templateAttributes['tID'] = $tID;
					$templateAttributes['templateHeader'] = trim((string)$templateXML->header);
					$templateAttributes['templateContent'] = trim((string)$templateXML->content);
					$templateAttributes['templateAlternateContent'] = trim((string)$templateXML->alternate);
					$templateAttributes['templateFooter'] = trim((string)$templateXML->footer);
					$templateAttributes['templateEmpty'] = trim((string)$templateXML->empty);
					
					sixeightdatadisplay::saveTemplate($templateAttributes);
				}
				
				
				if($totalImportErrors == 0) {
					$this->redirect('/dashboard/sixeightdatadisplay/tools','importSuccess');
				}
			}
		}
	}
	
	public function importSuccess() {
		$this->set('message',t('Template set imported successfully.'));
		$this->view();
	}
	
	public function convertTemplate() {
		Loader::model('datadisplay','datadisplay');
		$oldTemplate = DataDisplay::getTemplate(intval($_GET['tID']));
		$templateAttributes = array();
		$templateAttributes['fID'] = 0;
		$templateAttributes['templateName'] = $oldTemplate['templateName'];
		
		$tID = sixeightdatadisplay::createTemplate($templateAttributes,$oldTemplate['templateType']);
		
		$templateAttributes['templateHeader'] = $oldTemplate['templateHeader'];
		$templateAttributes['templateContent'] = $oldTemplate['templateContent'];
		$templateAttributes['templateAlternateContent'] = $oldTemplate['templateAlternateContent'];
		$templateAttributes['templateFooter'] = $oldTemplate['templateFooter'];
		$templateAttributes['templateEmpty'] = $oldTemplate['templateEmpty'];
		$templateAttributes['tID'] = $tID;
		
		sixeightdatadisplay::saveTemplate($templateAttributes);
		
		$this->redirect('/dashboard/sixeightdatadisplay/tools','convertTemplateSuccess');
	}
	
	public function convertTemplateSuccess() {
		$this->set('message',t('The template has been converted.'));
		$this->view();
	}
	
}

?>