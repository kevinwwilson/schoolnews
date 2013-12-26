<?php  
/**
 * Functions used to send mail in Concrete.
 * @package Helpers
 * @category Concrete
 * @author Andrew Embler <andrew@concrete5.org>
 * @copyright  Copyright (c) 2003-2008 Concrete5. (http://www.concrete5.org)
 * @license    http://www.concrete5.org/license/     MIT License
 */
 
defined('C5_EXECUTE') or die("Access Denied.");

class sixeightformHelper {

	function renderAlternateOptionGroup($og) {
		$ff = sixeightfield::getByID($og['ffID']);
		foreach($og['options'] as $option) {
			switch($ff->type) {
				case 'Dropdown':
					sixeightformHelper::renderSelectOption($option);
					break;
				case 'Multi-Select':
					sixeightformHelper::renderSelectOption($option);
					break;
				case 'Radio Button':
					sixeightformHelper::renderRadioButton($ff->ffID,0,$option);
					break;
				case 'Checkbox':
					sixeightformHelper::renderCheckbox($ff->ffID,0,$option);
					break;
			}
		}
	}
	
	function renderSelectOption($value,$isSelected=false) {
		echo '<option class="sem-option" value="' . htmlspecialchars($value) . '" ';
		if($isSelected) {
			echo ' selected="selected" ';
		}
		echo ' >' . htmlspecialchars($value) . '</option>';
	}
	
	function renderRadioButton($ffID,$oID,$value,$isChecked=false,$class='') {
		echo '<label class="sem-radio-button-label" for="sem-field-' . $ffID . '-' . $oID . '"><input class="sem-field sem-radio-button sem-field-' . $ffID . ' ' . $class . '" type="radio" name="' . $ffID . '" id="sem-field-' . $ffID . '-' . $oID . '" value="' . htmlspecialchars($value) . '" ';
		if($isChecked) {
			echo ' checked="checked" ';
		}
		echo ' />' . $value . '</label>';
	}
	
	function renderCheckbox($ffID,$oID,$value,$isChecked=false,$class='') {
		echo '<label class="sem-checkbox-label" for="sem-field-' . $ffID . '-' . $oID . '">';
		echo '<input class="sem-field sem-field-' . $ffID . ' sem-checkbox ' . $class . '" type="checkbox" name="' . $ffID . '[]" id="sem-field-' . $ffID . '-' . $oID . '" value="' . htmlspecialchars($value) . '" ';
		$defaultValues = explode("\r\n",$this->defaultValue);
		if($isChecked) {
			echo ' checked="checked" ';
		}
		echo ' /> ' . $value;
		echo '</label>';
	}

}