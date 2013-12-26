<?php 
defined('C5_EXECUTE') or die(_("Access Denied."));
class semGateway {
	
	function __construct() {
		define('SEM_PAYMENT_SUBMIT_BUTTON_VALUE', 'Pay with Paypal');
	}
	
	public function getFields() {
		$fields = array();
		$fields['paypal_email'] = array('label' => 'Paypal Email','required' => true);
		$fields['paypal_currency'] = array('label' => 'Currency Code','required' => false);
		$fields['paypal_description'] = array('label' => 'Item Description','required' => false);
		return $fields;
	}
	
}
?>