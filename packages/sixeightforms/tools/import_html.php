<?php 
Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');

$html = '<html><body>';
$html .= '<form>';
$html .= '<input type="text" />';
$html .= '<input type="email" />';
$html .= '<textarea name="hey"></textarea>';
$html .= '</form>';
$html .= '</body></html>';

$dom = new DOMDocument;
@$dom->loadHTML($html);
$forms = $dom->getElementsByTagName('form');

if($forms->length > 0) {
	foreach($forms as $form) {
			
		$inputFields = $form->getElementsByTagName('input');
		$textareaFields = $form->getElementsByTagName('textarea');
		$selectFields = $form->getElementsByTagName('select');
		
		if(($inputFields->length + $textareaFields->length + $selectFields->length) > 0) {
		
			//Create the form
			$data = array();
			$data['name'] = 'New Form';
			$f = sixeightform::create($data);
			echo 'Created new form';
			
			if($inputFields->length > 0) {
				foreach($inputFields as $input) {
					$ffData = array();
					
					switch($input->getAttribute('type')) {
						case 'checkbox':
							$ffData['type'] = 'Checkbox';
							break;
						case 'radio':
							$ffData['type'] = 'Radio Button';
							break;
						case 'radio':
							break;
						case 'email':
							$ffData['type'] = 'Email Address';
							break;
						case 'number':
							$ffData['type'] = 'Number';
							break;
						default:
							$ffData['type'] = 'Text (Single-line)';
					}
					
					if(($input->getAttribute('type') == 'checkbox') || ($input->getAttribute('type') == 'radio')) {
						$f->getFieldByHandle();
					}
					
					if($input->getAttribute('name') == '') {
						$ffData['label'] = t('Text Field');
					} else {
						$ffData['label'] = ucwords($input->getAttribute('name'));
					}
					
					$ffData['defaultValue'] = $input->getAttribute('value');
					$f->addField($ffData);
				}
			}
			
			if($textareaFields->length > 0) {
				foreach($textareaFields as $textarea) {
					$ffData = array();
					$ffData['type'] = 'Text (Multi-line)';
					if($input->getAttribute('name') == '') {
						$ffData['label'] = t('Textarea Field');
					} else {
						$ffData['label'] = ucwords($input->getAttribute('name'));
					}
					$f->addField($ffData);
				}
			}
			
			if($selectFields->length > 0) {
				foreach($selectFields as $input) {
					$ffData = array();
					
				}
			}
		} else {
			echo 'No fields';
		}
	}
} else {
	echo 'No forms found.';
}
