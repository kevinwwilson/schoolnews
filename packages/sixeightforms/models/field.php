<?php  

	Loader::model('answer_set_list','sixeightforms');

	class sixeightField extends Object {
	
		public function getByID($ffID) {
			$db = Loader::db();
			$fieldData = $db->getRow("SELECT * FROM sixeightformsFields WHERE ffID = ?",array($ffID));
			
			$field = new sixeightField;
			
			$field->ffID = $ffID;
			$field->fID = $fieldData['fID'];
			
			if($fieldData['type'] == 'Text (no user input)') {
				$field->label = $fieldData['text'];
			} else {
				$field->label = $fieldData['label'];
			}
			
			$field->shortLabel = sixeightField::shortenText(strip_tags($field->label),25);
			$field->handle = $fieldData['handle'];
			$field->text = $fieldData['text'];
			$field->type = $fieldData['type'];
			$field->defaultValue = $fieldData['defaultValue'];
			$field->width = $fieldData['width'];
			$field->height = $fieldData['height'];
			if($fieldData['maxLength'] == 0) {
				$field->maxLength = '';
			} else {
				$field->maxLength = $fieldData['maxLength'];
			}
			$field->layout = $fieldData['layout'];
			$field->format = $fieldData['format'];
			$field->toolbar = $fieldData['toolbar'];
			$field->required = $fieldData['required'];
			$field->sortPriority = $fieldData['sortPriority'];
			$field->price = $fieldData['price'];
			$field->qtyStart = $fieldData['qtyStart'];
			$field->qtyEnd = $fieldData['qtyEnd'];
			$field->qtyIncrement = $fieldData['qtyIncrement'];
			$field->eCommerceName = $fieldData['eCommerceName'];
			$field->isExpirationField = $fieldData['isExpirationField'];
			$field->dateFormat = $fieldData['dateFormat'];
			$field->indexable = $fieldData['indexable'];
			$field->requireUnique = $fieldData['requireUnique'];
			$field->fsID = $fieldData['fsID'];
			$field->urlParameter = $fieldData['urlParameter'];
			$field->populateWith = $fieldData['populateWith'];
			$field->cssClass =  $fieldData['cssClass'];
			$field->containerCssClass =  $fieldData['containerCssClass'];
			$field->minYear =  $fieldData['minYear'];
			$field->maxYear =  $fieldData['maxYear'];
			$field->validateSection =  $fieldData['validateSection'];
			$field->fsID =  $fieldData['fsID'];
			$field->excludeFromEmail = $fieldData['excludeFromEmail'];
			
			$field->options = $field->getOptions();		
			
			switch($field->populateWith) {
				case 'username':
					$u = new User();
					$field->defaultValue = $u->getUserName();
					break;
				case 'email':
					$u = new User();
					if($u->isLoggedIn()) {
						$ui = UserInfo::getByID($u->getUserID());
						$field->defaultValue = $ui->getUserEmail();
					}
					break;
				case 'uID':
					$u = new User();
					$field->defaultValue = $u->getUserID();
					break;
				default:
					if($field->populateWith != '') {
						$u = new User();
						if($u->isLoggedIn()) {
							$ui = UserInfo::getByID($u->getUserID());
							$field->defaultValue = $ui->getAttribute($field->populateWith);
						}
					}
			}
			
			return $field;
		}
		
		public function getByHandle($handle) {
			$db = Loader::db();
			$row = $db->getRow("SELECT ffID FROM sixeightformsFields WHERE handle = ?",array($handle));
			if(intval($row['ffID']) != 0) {
				return sixeightField::getByID($row['ffID']);
			} else {
				return false;
			}
		}
		
		public function create($data,$options='') {
			$db = Loader::db();
			
			if($data['handle'] == '') {
				$txt = Loader::helper('text');
				$data['handle'] = $txt->handle($data['label']);
			}
			
			$sqlData[] = $data['fID'];
			$sqlData[] = $data['label'];
			$sqlData[] = $data['handle'];
			$sqlData[] = $data['text'];
			$sqlData[] = $data['type'];
			$sqlData[] = $data['defaultValue'];
			$sqlData[] = $data['width'];
			$sqlData[] = $data['height'];
			$sqlData[] = intval($data['maxLength']);
			$sqlData[] = $data['layout'];
			$sqlData[] = $data['format'];
			$sqlData[] = $data['toolbar'];
			$sqlData[] = intval($data['groupWithPrevious']);
			$sqlData[] = intval($data['required']);
			$sqlData[] = floatval($data['price']);
			$sqlData[] = intval($data['qtyStart']);
			$sqlData[] = intval($data['qtyEnd']);
			$sqlData[] = intval($data['qtyIncrement']);
			$sqlData[] = $data['eCommerceName'];
			$sqlData[] = intval($data['isExpirationField']);
			$sqlData[] = $data['dateFormat'];
			$sqlData[] = intval($data['indexable']);
			$sqlData[] = intval($data['requireUnique']);
			$sqlData[] = $data['urlParameter'];
			$sqlData[] = $data['populateWith'];
			$sqlData[] = $data['cssClass'];
			$sqlData[] = $data['containerCssClass'];
			$sqlData[] = $data['minYear'];
			$sqlData[] = $data['maxYear'];
			$sqlData[] = $data['validateSection'];
			$sqlData[] = intval($data['fsID']);
			
			
			$db->execute("INSERT INTO sixeightformsFields (ffID, fID, label, handle, text, type, defaultValue, width, height, maxLength, layout, format, toolbar, groupWithPrevious, required, price, qtyStart, qtyEnd, qtyIncrement, eCommerceName, isExpirationField, dateFormat, indexable, requireUnique, urlParameter, populateWith, cssClass, containerCssClass, minYear, maxYear, validateSection, fsID, sortPriority) VALUES (0,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,999)",$sqlData);
			$ffID = $db->Insert_ID();
			
			$field = sixeightField::getByID($ffID);
			
			if($options != '') {
				$field->saveOptions($options);
			}
			
			if(($field->type == 'Text (no user input)') || ($field->type == 'Section Divider') || ($field->type == 'Next Button') || ($field->type == 'Previous Button')) {
				$field->changeExcludeFromEmailStatus();
			}
			
			return $field;
		}
		
		public function update($data,$options='') {
			$db = Loader::db();
			
			$sqlData = array();
			$sqlData[] = $data['label'];
			$sqlData[] = $data['handle'];
			$sqlData[] = $data['text'];
			$sqlData[] = $data['type'];
			$sqlData[] = $data['defaultValue'];
			$sqlData[] = $data['width'];
			$sqlData[] = $data['height'];
			$sqlData[] = intval($data['maxLength']);
			$sqlData[] = $data['layout'];
			$sqlData[] = $data['format'];
			$sqlData[] = $data['toolbar'];
			$sqlData[] = intval($data['groupWithPrevious']);
			$sqlData[] = intval($data['required']);
			$sqlData[] = floatval($data['price']);
			$sqlData[] = intval($data['qtyStart']);
			$sqlData[] = intval($data['qtyEnd']);
			$sqlData[] = intval($data['qtyIncrement']);
			$sqlData[] = $data['eCommerceName'];
			$sqlData[] = intval($data['isExpirationField']);
			$sqlData[] = $data['dateFormat'];
			$sqlData[] = intval($data['indexable']);
			$sqlData[] = intval($data['requireUnique']);
			$sqlData[] = $data['urlParameter'];
			$sqlData[] = $data['populateWith'];
			$sqlData[] = $data['cssClass'];
			$sqlData[] = $data['containerCssClass'];
			$sqlData[] = $data['minYear'];
			$sqlData[] = $data['maxYear'];
			$sqlData[] = $data['validateSection'];
			$sqlData[] = intval($data['fsID']);
			$sqlData[] = $data['ffID'];
			
			$db->execute("UPDATE sixeightformsFields SET label=?, handle=?, text=?, type=?, defaultValue=?, width=?, height=?, maxLength=?, layout=?, format=?, toolbar=?, groupWithPrevious=?, required=?, price=?, qtyStart=?, qtyEnd=?, qtyIncrement=?, eCommerceName=?, isExpirationField=?, dateFormat=?, indexable=?, requireUnique=?, urlParameter=?, populateWith=?, cssClass=?, containerCssClass=?, minYear=?, maxYear=?, validateSection=?, fsID=? WHERE ffID=?",$sqlData);
			if($options != '') {
				$this->saveOptions($options);
			}
		}
		
		public function updateLabel($newLabel) {
			$db = Loader::db();
			$db->execute("UPDATE sixeightformsFields SET label=? WHERE ffID=?",array($newLabel,$this->ffID));
		}
		
		public function updateText($newLabel) {
			$db = Loader::db();
			$db->execute("UPDATE sixeightformsFields SET text=? WHERE ffID=?",array($newLabel,$this->ffID));
		}
		
		public function duplicate($fID='') {
			$originalField = sixeightField::getByID($this->ffID);
			$originalOptions = $originalField->getOptionsValues($this->ffID);
			if($fID == '') {
				$data['fID'] = $originalField->fID;
			} else {
				$data['fID'] = $fID;
			}
			
			$data['label'] = $originalField->label;
			$data['handle'] = $originalField->handle;
			$data['text'] = $originalField->text;
			$data['type'] = $originalField->type;
			$data['defaultValue'] = $originalField->defaultValue;
			$data['width'] = $originalField->width;
			$data['height'] = $originalField->height;
			$data['maxLength'] = $originalField->maxLength;
			$data['layout'] = $originalField->layout;
			$data['format'] = $originalField->format;
			$data['toolbar'] = $originalField->toolbar;
			$data['groupWithPrevious'] = $originalField->groupWithPrevious;
			$data['required'] = $originalField->required;
			$data['price'] = $originalField->price;
			$data['qtyStart'] = $originalField->qtyStart;
			$data['qtyEnd'] = $originalField->qtyEnd;
			$data['qtyIncrement'] = $originalField->qtyIncrement;
			$data['eCommerceName'] = $originalField->eCommerceName;
			$data['isExpirationField'] = $originalField->isExpirationField;
			$data['dateFormat'] = $originalField->dateFormat;
			$data['indexable'] = $originalField->indexable;
			$data['requireUnique'] = $originalField->requireUnique;
			$data['urlParameter'] = $originalField->urlParameter;
			$data['populateWith'] = $originalField->populateWith;
			$data['cssClass'] = $originalField->cssClass;
			$data['containerCssClass'] = $originalField->containerCssClass;
			$data['minYear'] = $originalField->minYear;
			$data['maxYear'] = $originalField->maxYear;
			$data['validateSection'] = $originalField->validateSection;
			$data['fsID'] = $originalField->fsID;
			
			$newField = sixeightField::create($data);
			$newField->saveOptions($originalOptions);
			
			return $newField;
		}
		
		public function delete() {
			$db = Loader::db();
			$db->execute("UPDATE sixeightformsFields SET isDeleted=1 WHERE ffID=?",array($this->ffID));
			$f = sixeightform::getByID($this->fID);
			$f->clearAnswersCache();
		}
		
		public function getProperties() {
			$properties = array();
			foreach($this as $property => $value) {
				$properties[$property] = $value;
			}
			return $properties;
		}
		
		public function getOptions($autopopulate = true) {
			$db = Loader::db();
			$options = $db->getAll("SELECT * FROM sixeightformsOptions WHERE ffID = ? ORDER BY oID ASC",array($this->ffID));
			
			if($autopopulate) {
				$firstOption = $options[0];
				if(strpos($firstOption['value'],'ffID:') !== false) {
					$options = array();
					
					$newffID = intval(str_replace('ffID:','',$firstOption['value']));
					if(($newffID > 0) && ($newffID != $this->ffID)) {
						$newFF = sixeightField::getByID($newffID);
						if(intval($newFF->fID) > 0) {
							$asl = sixeightAnswerSetList::get($newFF->fID);
							$asl->setPageSize(0);
							$answerSets = $asl->getAnswerSets();
							foreach($answerSets as $as) {
								$options[] = array('value' => $as->answers[$newffID]['value']);
							}
						}
					}
				}
			}
			
			return $options;	
		}
		
		public function getOptionsValues() {
			$options = $this->getOptions();
			$values = array();
			foreach($options as $o) {
				$values[] = $o['value'];
			}
			return $values;
		}
		
		public function saveOptions($options) {
			$db = Loader::db();
			$db->execute("DELETE FROM sixeightformsOptions WHERE ffID=?",array($this->ffID));
			foreach($options as $option) {
				$option = trim($option);
				if ($option != '') {
					$db->execute("INSERT INTO sixeightformsOptions (oID,ffID,value) VALUES (0,?,?)",array($this->ffID,$option));
				}
			}
		}
		
		public function changeRequiredStatus() {
			$db = Loader::db();
			$ff = $db->getRow("SELECT required FROM sixeightformsFields WHERE ffID = ?",array($this->ffID));
			if($ff['required'] == '1') {
				$db->execute("UPDATE sixeightformsFields SET required = 0 WHERE ffID = ?",array($this->ffID));
				return 0;
			} else {
				$db->execute("UPDATE sixeightformsFields SET required = 1 WHERE ffID = ?",array($this->ffID));
				return 1;
			}
		}
		
		public function changeSearchableStatus() {
			$db = Loader::db();
			$ff = $db->getRow("SELECT indexable FROM sixeightformsFields WHERE ffID = ?",array($this->ffID));
			if($ff['indexable'] == '1') {
				$db->execute("UPDATE sixeightformsFields SET indexable = 0 WHERE ffID = ?",array($this->ffID));
				return 0;
			} else {
				$db->execute("UPDATE sixeightformsFields SET indexable = 1 WHERE ffID = ?",array($this->ffID));
				return 1;
			}
		}
		
		public function changeExcludeFromEmailStatus() {
			$db = Loader::db();
			$ff = $db->getRow("SELECT excludeFromEmail FROM sixeightformsFields WHERE ffID = ?",array($this->ffID));
			if($ff['excludeFromEmail'] == '1') {
				$db->execute("UPDATE sixeightformsFields SET excludeFromEmail = 0 WHERE ffID = ?",array($this->ffID));
				return 0;
			} else {
				$db->execute("UPDATE sixeightformsFields SET excludeFromEmail = 1 WHERE ffID = ?",array($this->ffID));
				return 1;
			}
		}
		
		public function shortenText($strString, $nLength = 15, $strTrailing = "...") {
			$nLength -= strlen($strTrailing);
			if (strlen($strString) > $nLength) {
				return substr($strString, 0, $nLength) . $strTrailing;
			} else {
				return $strString;
			}
		}
		
		public function saveFieldOrder($ffIDs) {
			$db = Loader::db();
			$i = 1;
			foreach($ffIDs as $ffID) {
				$db->execute("UPDATE sixeightformsFields set sortPriority=? WHERE ffID=?",array($i,$ffID));
				$i++;
			}
		}
		
		public function getFieldTypeHandle() {
			//Use to display the field type image in the /packages/sixeightforms/images/ folder
			
			$fieldType = $this->type;
			
			switch($fieldType) {
				case 'Text (Single-line)':
					$handle = 'textfield';
					break;
				case 'Text (Multi-line)';
					$handle = 'textarea';
					break;
				case 'Number':
					$handle = 'number';
					break;
				case 'Email Address':
					$handle = 'email';
					break;
				case 'Phone Number':
					$handle = 'phone';
					break;
				case 'Dropdown':
					$handle = 'dropdown';
					break;
				case 'Multi-Select':
					$handle = 'multi_select';
					break;
				case 'Radio Button':
					$handle = 'radio';
					break;
				case 'Checkbox':
					$handle = 'checkbox';
					break;
				case 'True/False':
					$handle = 'true_false';
					break;
				case 'Date':
					$handle = 'date';
					break;
				case 'Time':
					$handle = 'time';
					break;
				case 'File Upload':
					$handle = 'file_upload';
					break;
				case 'File from File Manager':
					$handle = 'file_manager';
					break;
				case 'WYSIWYG':
					$handle = 'wysiwyg';
					break;
				case 'Sellable Item':
					$handle = 'sellable_item';
					break;
				case 'Credit Card':
					$handle = 'credit_card';
					break;
				case 'Hidden':
					$handle = 'hidden';
					break;
				case 'Section Divider':
					$handle = 'section_divider';
					break;
				case 'Next Button':
					$handle = 'next';
					break;
				case 'Previous Button':
					$handle = 'previous';
					break;
				case 'Text (no user input)':
					$handle = 'text';
					break;
			}
			return $handle;
		}
		
		public function render($renderContainer = true,$renderLabel = true) {
			$f = sixeightform::getByID($this->fID);
			
			if($_GET[$this->urlParameter] != '') {
				$this->defaultValue = htmlspecialchars($_GET[$this->urlParameter]);
			}
			
			if($this->type == 'Hidden') {
				$renderContainer = false;
				$renderLabel = false;
			}
			
			if($renderContainer) {
				echo '<div class="sem-field-container ' . $this->containerCssClass . '" id="sem-field-container-' . $this->ffID . '" ';
				if($this->isRuleActionField()) {
					echo 'style="display:none"';
				}
				echo ' >';
			}
			
			if(($this->type == 'Radio Button') || ($this->type == 'Checkbox')) {
				echo '<fieldset class="sem-fieldset" id="sem-fieldset-' . $this->ffID . '">';
			}
			
			if($renderLabel) {
				$this->renderLabel($f->properties['requiredIndicator'],$f->properties['requiredColor']);
			}
			
			if ($this->type != 'Text (no user input)') {
				switch($this->type) {
					case 'Text (Single-line)':
						$this->renderTextField();
						break;
					case 'Text (Multi-line)';
						$this->renderTextareaField();
						break;
					case 'Number':
						$this->renderTextField();
						break;
					case 'Email Address':
						$this->renderTextField();
						break;
					case 'Phone Number':
						$this->renderTextField();
						break;
					case 'Dropdown':
						$this->renderSelectField();
						break;
					case 'Multi-Select':
						$this->renderMultiSelectField();
						break;
					case 'Radio Button':
						$this->renderRadioField();
						break;
					case 'Checkbox':
						$this->renderCheckboxField();
						break;
					case 'True/False':
						$this->renderTrueFalseField();
						break;
					case 'Date':
						$this->renderDateField();
						break;
					case 'Time':
						$this->renderTimeField();
						break;
					case 'File Upload':
						$this->renderFileField();
						break;
					case 'File from File Manager':
						$this->renderFileManagerField();
						break;
					case 'WYSIWYG':
						$this->renderWYSIWYGField();
						break;
					case 'Sellable Item':
						$this->renderSellableItemField();
						break;
					case 'Credit Card':
						$this->renderTextField();
						break;
					case 'Hidden':
						$this->renderHiddenField();
						break;
					case 'Section Divider':
						$this->renderSectionDividerField();
						break;
					case 'Next Button':
						$this->renderNextButtonField();
						break;
					case 'Previous Button':
						$this->renderPreviousButtonField();
						break;
					case 'Text (no user input)':
						$this->renderText();
						break;
				}
				if(($this->type == 'Radio Button') || ($this->type == 'Checkbox')) {
					echo '</fieldset>';
				}
			}
			
			if($renderContainer) {
				echo '</div><!--/.sem-field-container -->';
			}
		}
		
		public function renderLabel($requiredIndicator='*',$requiredColor='ff0000') {
			if(($this->type == 'Next Button') || ($this->type == 'Previous Button')) {
				return false;
			}
			if (($this->type == 'Radio Button') || ($this->type == 'Checkbox')) {
				echo '<div class="sem-legend" id="sem-legend-' . $this->ffID . '">' . $this->label;
				if(($this->isRequired()) && ($requiredIndicator != '')){
					echo ' <span class="sem-required-indicator" ';
					if($requiredColor != '') {
						echo 'style="color:#' . $requiredColor . '" ';
					}
					echo '>' . $requiredIndicator . '</span>';
				}
				echo'</div>';
			} elseif ($this->type == 'Text (no user input)') {
				echo $this->label;
			} elseif ($this->type != 'True/False') {
				echo '<label class="sem-label" for="sem-field-' . $this->ffID . '">' . $this->label;
				if(($this->isRequired()) && ($requiredIndicator != '')){
					echo ' <span class="sem-required-indicator" ';
					if($requiredColor != '') {
						echo 'style="color:#' . $requiredColor . '" ';
					}
					echo '>' . $requiredIndicator . '</span>';
				}
				echo '</label>';
			}
		}
		
		public function renderTextField() {
			$f = sixeightform::getByID($this->fID);
			echo '<input id="sem-field-' . $this->ffID . '" name="' . $this->ffID . '" class="sem-field sem-text ' . $this->cssClass . '" ';
			
			if($f->properties['useHTML5']) {
				if ($this->type == 'Email Address') {
					echo ' type="email"';
				} elseif ($this->type == 'Phone Number') {
					echo ' type="tel"';
				} elseif ($this->type == 'Number') {
					echo ' type="number"';
				} else {
					echo ' type="text"';
				}
			}
			
			if($this->width != '') {
				echo ' style="width:' . $this->width . 'px;" ';
			}
			if($this->defaultValue != '') {
				echo ' value="' . htmlspecialchars($this->defaultValue) . '" ';
			}
			if($this->maxLength != '') {
				echo ' maxlength="' . $this->maxLength . '" ';
			}
			echo ' />';
		}
		
		public function renderTextareaField() {
			if($this->maxLength != '') {
				echo '<script type="text/javascript">';
				echo '$(document).ready(function() {';
				echo '$("#sem-field-' . $this->ffID . '").maxlength(' . $this->maxLength . ')';
				echo '});';
				echo '</script>';
			}
			echo '<textarea id="sem-field-' . $this->ffID . '" name="' . $this->ffID . '" class="sem-field sem-textarea ' . $this->cssClass . '" ';
			if($this->width != '') {
				$widthCSS = 'width:' . $this->width . 'px;';
			}
			if($this->height != '') {
				$heightCSS = 'height:' . $this->height . 'px;';
			}
			if(($this->width != '') || ($this->height != '')) {
				echo ' style="' . $widthCSS . $heightCSS . '" ';
			}
			if($this->maxLength != '') {
				echo ' maxlength="' . $this->maxLength . '" ';
			}			
			echo '>';
			if($this->defaultValue != '') {
				echo htmlspecialchars($this->defaultValue);
			}
			echo '</textarea>';
		}
		
		public function renderSelectField() {
			echo '<select id="sem-field-' . $this->ffID . '" name="' . $this->ffID . '" class="sem-field sem-field-' . $this->ffID . ' sem-select ' . $this->cssClass . '">';
				if($this->required) {
					echo '<option class="sem-option" value=""></option>';
				}
				foreach($this->options as $option) {
					if(is_array($option)) {
						$optionValue = $option['value'];
					} else {
						$optionValue = $option;
					}
					echo '<option class="sem-option" value="' . htmlspecialchars($optionValue) . '" ';
					if($optionValue == $this->defaultValue) {
						echo ' selected="selected" ';
					}
					echo ' >' . htmlspecialchars($optionValue) . '</option>';
				}
			echo '</select>';
		}
		
		public function renderMultiSelectField() {
			if(intval($this->height) == 0) {
				$height = 5;
			} else {
				$height = $this->height;
			}
			
			if(intval($this->maxLength) > 0) {
				echo '<script type="text/javascript">';
				echo '$(document).ready(function() {';
				echo 'var last_valid_selection = null;';
				echo '$(\'.sem-field-' . $this->ffID . '\').change(function(e) {';
				echo 'if($(this).val().length > ' . $this->maxLength . ') {';
				echo ' $(this).val(last_valid_selection);';
				echo '} else {';
				echo 'last_valid_selection = $(this).val();';
				echo '}';
				echo '});';
				echo '});';
				echo '</script>';
			}
			
			echo '<select id="sem-field-' . $this->ffID . '" name="' . $this->ffID . '[]" class="sem-field sem-field-' . $this->ffID . ' sem-select ' . $this->cssClass . '" multiple="multiple" size="' . $height . '">';
				foreach($this->options as $option) {
					if(is_array($option)) {
						$optionValue = $option['value'];
					} else {
						$optionValue = $option;
					}
					echo '<option class="sem-option" value="' . htmlspecialchars($optionValue) . '" ';
					$defaultValues = explode("\r\n",$this->defaultValue);
					if(is_array($defaultValues)) {
					foreach($defaultValues as $dv) {
						if($optionValue == $dv) {
							echo ' selected="selected" ';
						}
					}
				}
					echo ' >' . htmlspecialchars($optionValue) . '</option>';
				}
			echo '</select>';
		}
		
		public function renderRadioField() {
			$i = 1;
			foreach($this->options as $option) {
				if(is_array($option)) {
					$optionValue = $option['value'];
				} else {
					$optionValue = $option;
				}
				echo '<label class="sem-radio-button-label" for="sem-field-' . $this->ffID . '-' . $i . '">';
				echo '<input class="sem-field sem-radio-button sem-field-' . $this->ffID . ' ' . $this->cssClass . '" type="radio" name="' . $this->ffID . '" id="sem-field-' . $this->ffID . '-' . $i . '" value="' . htmlspecialchars($optionValue) . '" ';
				if($optionValue == $this->defaultValue) {
					echo ' checked="checked" ';
				}
				echo ' />' . $optionValue;
				echo '</label>';
				$i++;
			}
		}
		
		public function renderCheckboxField() {
			$i = 1;
			
			if(intval($this->maxLength) > 0) {
				echo '<script type="text/javascript">';
				echo '$(document).ready(function() {';
				echo '$(\'.sem-field-' . $this->ffID . '\').click(function(e) {';
				echo 'if($(\'.sem-field-' . $this->ffID . '\').filter(\':checked\').length > ' . $this->maxLength . ') {';
				echo 'e.preventDefault();';
				echo '$(this).parent().removeClass(\'active\')';
				echo '}';
				echo '});';
				echo '});';
				echo '</script>';
			}
			
			foreach($this->options as $option) {
				if(is_array($option)) {
					$optionValue = $option['value'];
				} else {
					$optionValue = $option;
				}
				echo '<label class="sem-checkbox-label" for="sem-field-' . $this->ffID . '-' . $i . '">';
				echo '<input class="sem-field sem-field-' . $this->ffID . ' sem-checkbox ' . $this->cssClass . '" type="checkbox" name="' . $this->ffID . '[]" id="sem-field-' . $this->ffID . '-' . $i . '" value="' . htmlspecialchars($optionValue) . '" ';
				$defaultValues = explode("\r\n",$this->defaultValue);
				if(is_array($defaultValues)) {
					foreach($defaultValues as $dv) {
						if($optionValue == $dv) {
							echo ' checked="checked" ';
						}
					}
				}
				echo ' /> ' . $optionValue;
				echo '</label>';
				$i++;
			}
		}
		
		public function renderTrueFalseField() {
			echo '<label class="sem-checkbox-label" for="sem-field-' . $this->ffID . '"><input class="sem-field sem-field-' . $this->ffID . ' sem-checkbox ' . $this->cssClass . '" type="checkbox" name="' . $this->ffID . '" id="sem-field-' . $this->ffID . '" value="true" /> ' . $this->label . '</label>';
		}
		
		public function renderDateField() {
			if($this->dateFormat == '') {
				$dateFormat = 'yy-mm-dd';
			} else {
				$dateFormat = $this->dateFormat;
			}
			echo '<input class="sem-field sem-date ' . $this->cssClass . '" id="sem-field-' . $this->ffID . '" name="' . $this->ffID . '" type="text" value="' . $this->defaultValue . '" />';
			echo '<script type="text/javascript">';
			echo '$(document).ready(function() { $("#sem-field-' . $this->ffID . '").datepicker({ changeYear: true, changeMonth: true, showAnim: \'fadeIn\', dateFormat: \'' . $dateFormat . '\'';
			if(($this->minYear != '') && ($this->maxYear != '')) {
				echo ', yearRange: "' . intval($this->minYear) . ':' . intval($this->maxYear) . '"';
			}
			echo ' }); });';
			echo '</script>';
		}
		
		public function renderTimeField() {
			if($this->defaultValue != '') {
				$timeParts = explode(' ',$this->defaultValue);
				$time = explode(':',trim($timeParts[0]));
				$hour = trim($time[0]);
				$minute = trim($time[1]);
				$ampm = trim($timeParts[1]);
			}
			echo '<select id="sem-field-' . $this->ffID . '" name="' . $this->ffID . '[0]" class="sem-field sem-select ' . $this->cssClass . '"><option value=""></option>';
			for($i=1;$i<=12;$i++) {
				echo '<option value="' . $i . '"';
				if($hour == $i) {
					echo ' selected="selected" ';
				}
				echo '>' . $i . '</option>';
			}
			echo '</select>:';
			echo '<select id="sem-field-' . $this->ffID . '" name="' . $this->ffID . '[1]" class="sem-field sem-select ' . $this->cssClass . '"><option value=""></option>';
			for($i=0;$i<=59;$i++) {
				if($i < 10) {
					$val = '0' . $i;	
				} else {
					$val = $i;
				}
				echo '<option value="' . $val . '"';
				if($minute == $val) {
					echo ' selected="selected" ';
				}
				echo '>' . $val . '</option>';
			}
			echo '</select>';
			echo '<select id="sem-field-' . $this->ffID . '" name="' . $this->ffID . '[2]" class="sem-field sem-select ' . $this->cssClass . '"><option value=""></option>';
			echo '<option value="AM"';
			if($ampm == 'AM') {
				echo ' selected="selected" ';
			}
			echo '>AM</option>';
			echo '<option value="PM"';
			if($ampm == 'PM') {
				echo ' selected="selected" ';
			}
			echo '>PM</option>';
			echo '</select>';
		}
		
		public function renderFileField() {
			if($this->defaultValue != '') {
				$this->renderFileManagerField();
			} else {
				echo '<input type="hidden" id="sem-field-' . $this->ffID . '" name="' . $this->ffID . '" value="' . $this->defaultValue . '" />';
				echo '<div class="sem-field sem-file ' . $this->cssClass . '" id="sem-file-' . $this->ffID . '"></div>';
			}
		}
		
		public function renderFileManagerField() {
			$file=File::getByID($this->defaultValue);
			if(($file) && (is_numeric($this->defaultValue))) {
				$fv=$file->getApprovedVersion();
				if($fv->getFileName() == '') {
					$linkText = t('Select file');
				} else {
					$linkText = $fv->getFileName();
				}
			} else {
				$linkText = t('Select file');
			}
			
			$al = Loader::helper('concrete/asset_library');
			echo $al->file('sem-field-' . $this->ffID,$this->ffID, t('Choose File'), $file);
			
			//echo '<a id="sem-file-selector-' . $this->ffID . '" class="sem-field sem-file-selector ' . $this->cssClass . '" href="' . DIR_REL . '/index.php/tools/required/files/search_dialog?search=1" dialog-modal="false" onclick="currentFileField=' . $this->ffID . ';">' . $linkText . '</a> <a href="javascript:void(0);" onclick="$(\'#sem-field-' . $this->ffID . '\').val(0);$(\'#sem-file-selector-' . $this->ffID . '\').html(\'' . t('Select file') . '\');">[x]</a>';
			//echo '<input id="sem-field-' . $this->ffID . '" type="hidden" name="' . $this->ffID . '" value="' . $this->defaultValue . '" />';
		}
		
		public function renderWYSIWYGField() {
			if($this->toolbar == '1') {
				Loader::element('editor_controls');
			}
			
			echo '<textarea name="' . $this->ffID . '" id="sem-wysiwyg-' . $this->ffID . '" ';
			
			if($this->width != '') {
				$widthCSS = 'width:' . $this->width . 'px;';
			}
			if($this->height != '') {
				$heightCSS = 'height:' . $this->height . 'px;';
			}
			if(($this->width != '') || ($this->height != '')) {
				echo ' style="' . $widthCSS . $heightCSS . '" ';
			}
			
			switch($this->format) {
				case 'basic':
					echo 'class="sem-field sem-field-wysiwyg sem-wysiwyg-basic ' . $this->cssClass . '"';
					break;
				case 'simple':
					echo 'class="sem-field sem-field-wysiwyg sem-wysiwyg-simple ' . $this->cssClass . '"';
					break;
				case 'advanced':
					echo 'class="sem-field sem-field-wysiwyg sem-wysiwyg-advanced ' . $this->cssClass . '"';
					break;
				case 'office':
					echo 'class="sem-field sem-field-wysiwyg sem-wysiwyg-office ' . $this->cssClass . '"';
					break;
			}
			
			echo '>';
			if($this->defaultValue != '') {
				echo htmlspecialchars($this->defaultValue);
			}
			echo '</textarea>';
			
			/* Removed on 12/9/10 due to addition of tinyMCE.triggerSave() added in view.php on the Advanced Form block
			echo '<input type="hidden" class="sem-wysiwyg-hidden" id="sem-field-' . $this->ffID . '" />';
			*/
		}
		
		public function renderSellableItemField() {
			$f = sixeightform::getByID($this->fID);
			$currencySymbol = $f->properties['currencySymbol'];
			
			if ($this->price == 0) { //Allow for donations
				echo $currencySymbol . '<input type="text" class="sem-field sem-text ' . $this->cssClass . '" id="sem-field-' . $this->ffID . '" name="' . $this->ffID . '" size="3" />';
			} elseif (($this->qtyStart == $this->qtyEnd) && ($this->qtyStart > 0)) { //Display automatically checked box: User must purchase the product
				echo '<input type="hidden" class="sem-field sem-hidden ' . $this->cssClass . '" id="sem-field-' . $this->ffID . '" name="' . $this->ffID .'" value="' . $this->qtyEnd . '" /> ' . $currencySymbol . number_format($this->price,2);
			} elseif (($this->qtyEnd == 1) && ($this->qtyStart == 0)) { //Display regular checkbox: User has an option to purchase only 1 of the product
				echo '<input type="checkbox" class="sem-field sem-checkbox ' . $this->cssClass . '" id="sem-field-' . $this->ffID . '" name="' . $this->ffID .'" value="' . $this->qtyEnd . '" /> ' . $currencySymbol . number_format($this->price,2);
			} elseif (($this->qtyEnd - $this->qtyStart > 1) && ($this->qtyIncrement > 0)) { //Display dropdown: User selects how many to purchase
				echo '<select class="sem-field sem-select ' . $this->cssClass . '" id="sem-field-' . $this->ffID . '" name="' . $this->ffID . '">';
				for($i = $this->qtyStart;$i<=$this->qtyEnd;$i+=$this->qtyIncrement) {
					echo '<option value="' . $i . '">' . $i . '</option>';
				}
				echo '</select> x ' . $currencySymbol . number_format($this->price,2);
			} else { //Allow for donations
				echo $currencySymbol . '<input type="text" class="sem-field sem-text ' . $this->cssClass . '" id="sem-field-' . $this->ffID . '" name="' . $this->ffID . '" size="3" />';
			}
		}
		
		public function renderHiddenField() {
			echo '<input type="hidden" class="sem-field sem-hidden ' . $this->cssClass . '" id="sem-field-' . $this->ffID . '" name="' . $this->ffID .'" value="' . $this->defaultValue . '" />';
		}
		
		public function renderSectionDividerField() {
		}
		
		public function renderNextButtonField() {
			if($this->validateSection) {
				$validateClass = 'validate-section';
			} else {
				$validateClass = '';
			}
			echo '<button name="' . $this->ffID .'" class="sem-field sem-button sem-next-button ' . $validateClass . ' ' . $this->cssClass . '">' . $this->label . '</button>';
		}
		
		public function renderPreviousButtonField() {
			if($this->validateSection) {
				$validateClass = 'validate-section';
			} else {
				$validateClass = '';
			}
			echo '<button name="' . $this->ffID .'" class="sem-field sem-button sem-previous-button ' . $validateClass . ' ' . $this->cssClass . '">' . $this->label . '</button>';
		}
		
		public function renderText() {
			if($this->cssClass != '') {
				echo '<div class="' . $this->cssClass . '">';
			}
			echo $this->label;
			if($this->cssClass != '') {
				echo '</div>';
			}
		}
		
		public function isRequired() {
			if($this->required == '1') {
				return true;
			} else {
				return false;
			}
		}
		
		public function isUnique() {
			if($this->requireUnique == '1') {
				return true;
			} else {
				return false;
			}
		}
		
		public function validateUniqueness($value) {
			$db = Loader::db();
			$row = $db->getRow("SELECT asID FROM sixeightformsAnswers WHERE ffID = ? AND value = ?",array($this->ffID,$value));
			if(intval($row['asID']) != 0) {
				Loader::model('answer_set','sixeightforms');
				if(sixeightAnswerSet::getByID($row['asID'])) {
					//Duplicate answer exists, so don't validate
					return false;
				} else {
					//Answer is unique
					return true;
				}
			}
			return true;
		}
		
		public function addRule($comparison,$value,$action,$actionField,$ogID=0) {
			$db = Loader::db();
			$db->execute("INSERT INTO sixeightformsRules (rID,fID,ffID,comparison,value,action,actionField,ogID) VALUES (0,?,?,?,?,?,?,?)",array($this->fID,$this->ffID,$comparison,$value,$action,$actionField,$ogID));
		}
		
		public function isRuleActionField() {
			$db = Loader::db();
			$rules = $db->getAll("SELECT actionField FROM sixeightformsRules WHERE actionField = ?",array($this->ffID));
			if(count($rules) > 0) {
				return true;
			} else {
				return false;
			}
		}
		
		public function getAlternateOptions() {
			$db = Loader::db();
			$optionGroups = $db->getAll("SELECT ogID, name FROM sixeightformsAlternateOptionGroups WHERE ffID = ? ORDER BY ogID",array($this->ffID));
			$oGroups = array();
			$i = 0;
			foreach($optionGroups as $og) {
				$oGroups[$i]['ogID'] = $og['ogID'];
				$oGroups[$i]['name'] = $og['name'];
				
				$options = $db->getAll("SELECT value FROM sixeightformsAlternateOptions WHERE ogID = ? ORDER BY oID",array($og['ogID']));
				foreach($options as $o) {
					$oGroups[$i]['options'][] = $o['value'];
				}
				$i++;
			}
			return $oGroups;
		}
		
		public function addAlternateOptionGroup($name,$options) {
			$db = Loader::db();
			if(count($options) > 0) {
				$db->execute("INSERT INTO sixeightformsAlternateOptionGroups (ogID,ffID,name) VALUES (0,?,?)",array($this->ffID,$name));
				$ogID = $db->Insert_ID();
				foreach($options as $option) {
					$option = trim($option);
					if ($option != '') {
						$db->execute("INSERT INTO sixeightformsAlternateOptions (oID,ogID,ffID,value) VALUES (0,?,?,?)",array($ogID,$this->ffID,$option));
					}
				}
			}
			return $ogID;
		}
		
		public function clearAlternateOptions() {
			$db = Loader::db();
			$db->execute("DELETE FROM sixeightformsAlternateOptionGroups WHERE ffID = ?",array($this->ffID));
			$db->execute("DELETE FROM sixeightformsAlternateOptions WHERE ffID = ?",array($this->ffID));
		}
	}
?>