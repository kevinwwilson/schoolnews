<?php 
defined('C5_EXECUTE') or die(_("Access Denied."));

$ch = Page::getByPath("/dashboard/sixeightforms/forms");
$chp = new Permissions($ch);
if (!$chp->canRead()) {
	die(_("Access Denied."));
}

Loader::library('view');
Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');
Loader::model('file_set');

$h = Loader::helper('concrete/interface');
$uh = Loader::helper('concrete/urls');

$form = sixeightForm::getByID(intval($_GET['fID']));
$forms = sixeightForm::getAll();
?>
<script type="text/javascript">
var ffID = <?php  echo intval($_GET['ffID']); ?>;

function createField() {
	$('#newFieldForm').submit();
}

	$('.tabs li a').click(function(e) {
		e.preventDefault();
		$('.tabs li').removeClass('active');
		$(this).parent().addClass('active');
		$('.ccm-tab').hide();
		$($(this).attr('href')).show();
	});
	
	$('.field-type-button').click(function() {
		selectFieldType($(this));
	});

	//Select a field type
	function selectFieldType(fieldTypeButton) {
		var fieldType = fieldTypeButton.attr('title');
		$('#type').val(fieldType);
		$('#field-type-title a').html(fieldTypeButton.html());
		$('#tab-type').fadeOut('fast', function() {
			$('.field-row').hide();
			$('#field-row-title').show();
			$('#field-row-submit').show();
			switch(fieldType) {
				case 'Text (Single-line)' :
					$('#field-row-required').show();
					$('#field-row-label').show();
					$('#field-row-default-value').show();
					$('#field-row-width').show();
					$('#field-row-max-input').show();
					$('#field-row-grouping').hide();
					$('#field-row-handle').show();
					$('#field-row-ecommerce-name').show();
					$('#field-row-indexable').show();
					$('#field-row-unique').show();
					$('#field-row-url-parameter').show();
					$('#field-row-populate-with').show();
					$('#field-row-class').show();
					$('#field-row-container-class').show();
					$('#max-length-label').html('<?php  echo t('Maximum Input'); ?>');
					break;
				case 'Text (Multi-line)' :
					$('#field-row-required').show();
					$('#field-row-label').show();
					$('#field-row-default-value').show();
					$('#field-row-width').show();
					$('#field-row-height').show();
					$('#field-row-max-input').show();
					$('#field-row-grouping').hide();
					$('#field-row-handle').show();
					$('#field-row-ecommerce-name').show();
					$('#field-row-indexable').show();
					$('#field-row-unique').show();
					$('#field-row-url-parameter').show();
					$('#field-row-populate-with').show();
					$('#field-row-class').show();
					$('#field-row-container-class').show();
					$('#max-length-label').html('<?php  echo t('Maximum Input'); ?>');
				case 'Number' :
					$('#field-row-required').show();
					$('#field-row-label').show();
					$('#field-row-default-value').show();
					$('#field-row-width').show();
					$('#field-row-max-input').show();
					$('#field-row-grouping').hide();
					$('#field-row-handle').show();
					$('#field-row-ecommerce-name').show();
					$('#field-row-indexable').show();
					$('#field-row-unique').show();
					$('#field-row-url-parameter').show();
					$('#field-row-populate-with').show();
					$('#field-row-class').show();
					$('#field-row-container-class').show();
					$('#max-length-label').html('<?php  echo t('Maximum Input'); ?>');
					break;
				case 'Date' :
					$('#field-row-required').show();
					$('#field-row-label').show();
					$('#field-row-default-value').show();
					$('#field-row-width').show();
					$('#field-row-max-input').show();
					$('#field-row-grouping').hide();
					$('#field-row-handle').show();
					$('#field-row-date-format').show();
					$('#field-row-min-year').show();
					$('#field-row-max-year').show();
					$('#field-row-expiration').show();
					$('#field-row-ecommerce-name').show();
					$('#field-row-indexable').show();
					$('#field-row-unique').show();
					$('#field-row-url-parameter').show();
					$('#field-row-populate-with').show();
					$('#field-row-class').show();
					$('#field-row-container-class').show();
					$('#max-length-label').html('<?php  echo t('Maximum Input'); ?>');
					break;
				case 'Time' :
					$('#field-row-required').show();
					$('#field-row-label').show();
					$('#field-row-grouping').hide();
					$('#field-row-handle').show();
					$('#field-row-ecommerce-name').show();
					$('#field-row-indexable').show();
					$('#field-row-unique').show();
					$('#field-row-url-parameter').show();
					$('#field-row-populate-with').show();
					$('#field-row-class').show();
					$('#field-row-container-class').show();
					break;
				case 'File Upload' :
					$('#field-row-required').show();
					$('#field-row-label').show();
					$('#field-row-grouping').hide();
					$('#field-row-handle').show();
					$('#field-row-indexable').show();
					$('#field-row-unique').show();
					$('#field-row-url-parameter').show();
					$('#field-row-populate-with').show();
					$('#field-row-class').show();
					$('#field-row-container-class').show();
					$('#field-row-fileset').show();
					break;
				case 'File from File Manager' :
					$('#field-row-required').show();
					$('#field-row-label').show();
					$('#field-row-grouping').hide();
					$('#field-row-handle').show();
					$('#field-row-indexable').show();
					$('#field-row-unique').show();
					$('#field-row-url-parameter').show();
					$('#field-row-populate-with').show();
					$('#field-row-class').show();
					$('#field-row-container-class').show();
					break;
				case 'Email Address' :
					$('#field-row-required').show();
					$('#field-row-label').show();
					$('#field-row-default-value').show();
					$('#field-row-width').show();
					$('#field-row-max-input').show();
					$('#field-row-grouping').hide();
					$('#field-row-handle').show();
					$('#field-row-ecommerce-name').show();
					$('#field-row-indexable').show();
					$('#field-row-unique').show();
					$('#field-row-url-parameter').show();
					$('#field-row-populate-with').show();
					$('#field-row-class').show();
					$('#field-row-container-class').show();
					$('#max-length-label').html('<?php  echo t('Maximum Input'); ?>');
					break;
				case 'Phone Number' :
					$('#field-row-required').show();
					$('#field-row-label').show();
					$('#field-row-default-value').show();
					$('#field-row-width').show();
					$('#field-row-max-input').show();
					$('#field-row-grouping').hide();
					$('#field-row-handle').show();
					$('#field-row-ecommerce-name').show();
					$('#field-row-indexable').show();
					$('#field-row-unique').show();
					$('#field-row-url-parameter').show();
					$('#field-row-populate-with').show();
					$('#field-row-class').show();
					$('#field-row-container-class').show();
					$('#max-length-label').html('<?php  echo t('Maximum Input'); ?>');
					break;
				case 'Dropdown' :
					$('#field-row-required').show();
					$('#field-row-label').show();
					$('#field-row-default-value').show();
					$('#field-row-grouping').hide();
					$('#field-row-handle').show();
					$('#field-row-auto-populate').show();
					$('#field-row-options').show();
					$('#field-row-ecommerce-name').show();
					$('#field-row-indexable').show();
					$('#field-row-unique').show();
					$('#field-row-url-parameter').show();
					$('#field-row-populate-with').show();
					$('#field-row-class').show();
					$('#field-row-container-class').show();
					break;
				case 'Multi-Select' :
					$('#field-row-required').show();
					$('#field-row-label').show();
					$('#field-row-default-value').show();
					$('#field-row-grouping').hide();
					$('#field-row-handle').show();
					$('#field-row-auto-populate').show();
					$('#field-row-options').show();
					$('#field-row-ecommerce-name').show();
					$('#field-row-indexable').show();
					$('#field-row-unique').show();
					$('#field-row-height').show();
					$('#field-row-url-parameter').show();
					$('#field-row-populate-with').show();
					$('#field-row-class').show();
					$('#field-row-container-class').show();
					$('#field-row-max-input').show();
					$('#max-length-label').html('<?php  echo t('Maximum Selectable Options'); ?>');
					break;
				case 'Radio Button' :
					$('#field-row-required').show();
					$('#field-row-label').show();
					$('#field-row-default-value').show();
					$('#field-row-grouping').hide();
					$('#field-row-handle').show();
					$('#field-row-auto-populate').show();
					$('#field-row-options').show();
					$('#field-row-ecommerce-name').show();
					$('#field-row-indexable').show();
					$('#field-row-unique').show();
					$('#field-row-url-parameter').show();
					$('#field-row-populate-with').show();
					$('#field-row-class').show();
					$('#field-row-container-class').show();
					break;
				case 'Checkbox' :
					$('#field-row-required').show();
					$('#field-row-label').show();
					$('#field-row-default-value').show();
					$('#field-row-grouping').hide();
					$('#field-row-handle').show();
					$('#field-row-auto-populate').show();
					$('#field-row-options').show();
					$('#field-row-ecommerce-name').show();
					$('#field-row-indexable').show();
					$('#field-row-unique').show();
					$('#field-row-url-parameter').show();
					$('#field-row-populate-with').show();
					$('#field-row-class').show();
					$('#field-row-container-class').show();
					$('#field-row-max-input').show();
					$('#max-length-label').html('<?php  echo t('Maximum Selectable Options'); ?>');
					break;
				case 'True/False' :
					$('#field-row-required').show();
					$('#field-row-label').show();
					$('#field-row-default-value').hide();
					$('#field-row-grouping').hide();
					$('#field-row-handle').show();
					$('#field-row-options').hide();
					$('#field-row-ecommerce-name').hide();
					$('#field-row-indexable').hide();
					$('#field-row-unique').show();
					$('#field-row-url-parameter').show();
					$('#field-row-populate-with').show();
					$('#field-row-class').show();
					$('#field-row-container-class').show();
					break;
				case 'WYSIWYG' :
					$('#field-row-required').show();
					$('#field-row-label').show();
					$('#field-row-default-value').show();
					$('#field-row-grouping').hide();
					$('#field-row-handle').show();
					$('#field-row-width').show();
					$('#field-row-height').show();
					$('#field-row-format').show();
					$('#field-row-toolbar').show();
					$('#field-row-ecommerce-name').show();
					$('#field-row-indexable').show();
					$('#field-row-unique').show();
					$('#field-row-url-parameter').show();
					$('#field-row-populate-with').show();
					$('#field-row-class').show();
					$('#field-row-container-class').show();
					break;
				case 'Sellable Item' :
					$('#field-row-required').show();
					$('#field-row-label').show();
					$('#field-row-default-value').show();
					$('#field-row-grouping').hide();
					$('#field-row-handle').show();
					$('#field-row-price').show();
					$('#field-row-start-quantity').show();
					$('#field-row-end-quantity').show();
					$('#field-row-increment').show();
					$('#field-row-ecommerce-name').show();
					$('#field-row-indexable').show();
					$('#field-row-unique').show();
					$('#field-row-url-parameter').show();
					$('#field-row-populate-with').show();
					$('#field-row-class').show();
					$('#field-row-container-class').show();
					break;
				case 'Credit Card' :
					$('#field-row-required').show();
					$('#field-row-label').show();
					$('#field-row-default-value').show();
					$('#field-row-grouping').hide();
					$('#field-row-handle').show();
					$('#field-row-ecommerce-name').show();
					$('#field-row-indexable').show();
					$('#field-row-unique').show();
					$('#field-row-url-parameter').show();
					$('#field-row-populate-with').show();
					$('#field-row-class').show();
					$('#field-row-container-class').show();
					break;
				case 'Hidden' :
					$('#field-row-label').show();
					$('#field-row-default-value').show();
					$('#field-row-ecommerce-name').show();
					$('#field-row-indexable').show();
					$('#field-row-unique').show();
					$('#field-row-url-parameter').show();
					$('#field-row-populate-with').show();
					$('#field-row-class').show();
					$('#field-row-container-class').show();
					$('#field-row-handle').show();
					break;
				case 'Section Divider' :
					$('#label').val('<?php  echo t('Section Divider'); ?>');
					$('#field-row-label').show();
					break;
				case 'Next Button' :
					$('#field-row-label').show();
					$('#field-row-class').show();
					$('#field-row-container-class').show();
					$('#field-row-validate').show();
					break;
				case 'Previous Button' :
					$('#field-row-label').show();
					$('#field-row-class').show();
					$('#field-row-container-class').show();
					$('#field-row-validate').show();
					break;
				case 'Text (no user input)' :
					$('#field-row-text').show();
					$('#field-row-container-class').show();
					break;
			}
			$('#tab-options').fadeIn('fast');
		});
	}
	
	//Change field type
	$('#field-type-title a').click(function(e) {
		e.preventDefault();
		$('#tab-options').fadeOut('fast', function() {
			$('#tab-type').fadeIn('fast');
		});
	});
	
	$('#form-list').change(function() {
		$('#field-list-container').hide();
		$('#field-list').html('');
		if($(this).val() > 0) {
			$.ajax({
				url: '<?php  echo $uh->getToolsURL('json_fields','sixeightforms'); ?>?fID=' + $(this).val(),
				dataType: 'json',
				success: function(fields) {
					if(fields.length > 0) {
						$('#field-list').append('<option value="0">Select a field</option>');
						$.each(fields,function() {
							if(this.ffID != ffID) {
								$('#field-list').append('<option value="' + this.ffID + '">' + this.shortLabel + '</option>');
							}
						});
					} else {
						alert('<?php  echo t('No fields to choose from'); ?>');
					}
					$('#field-list-container').show();
				}
			});
		}
	});
	
	$('#field-list').change(function() {
		$('#options').val('ffID:' + $(this).val());
	});
<?php  
if(intval($_GET['ffID']) != 0) {
	$formsURL=View::url('/dashboard/sixeightforms/forms', 'updateField');
	$field = sixeightField::getByID(intval($_GET['ffID']));
	$options = $field->getOptions(false);

	if(is_array($options)) {
		$optionList = '';
		foreach($options as $option) {
			$optionList .= $option['value'] . "\n";
		}
		
		if(strpos($options[0]['value'],'ffID:') !== false) {
			$autopopulateFFID = intval(str_replace('ffID:','',$options[0]['value']));
			$autopopulateField = sixeightField::getByID($autopopulateFFID);
			$autopopulateFID = $autopopulateField->fID;
?>
	$('#form-list-container').show();
	$('#field-list-container').show();
	$('#options-container').hide();
	$('#form-list').val(<?php  echo $autopopulateFID; ?>);
<?php 
		}
	}
?>
	$('.field-type-button').each(function() {
		if($(this).attr('title') == '<?php  echo $field->type; ?>') {
			selectFieldType($(this));
		}
	});

<?php  
	foreach($field->getProperties() as $property => $value) {
		if(!is_array($value)) {
?>
			$('#<?php  echo $property; ?>').val('<?php  echo str_replace("\r\n",'',str_replace("'","\'",$value)); ?>');
<?php  
		}
	}
} else {
	$formsURL=View::url('/dashboard/sixeightforms/forms', 'addField');
}
?>

	$('.field-type-button').each(function() {
		if($(this).attr('id') == '<?php  echo $_GET['type']; ?>') {
			selectFieldType($(this));	
		}
	});
	
	//Submit form
	function createField() {
		$('#newFieldForm').submit();
	}
	
</script>
<style type="text/css">
.field-type-box {
	border-bottom:solid 1px #dedede;
	position:relative;
}

div.field-type-button {
	margin-top:2px;
	margin-bottom:2px;
	font-size:14px;
	padding:6px;
	border:solid 1px #fafafa;
	vertical-align:middle;
	color:#666666;
}

div.field-type-button:hover {
	background-color:#d9e7ff;
	border:solid 1px #94a7c7;
	cursor:pointer;
}

.field-type-title {
	height:18px;
}

.field-type-title {
	border:solid 1px #94a7c7;
	background-color:#d9e7ff;
}
</style>

<script type="text/javascript">
function populate_options(option_set) {
	switch(option_set) {
		case 'days':
			$('#form-list-container').hide();
			$('#field-list-container').hide();
			$('#options-container').show();
			$('#numbers_form').hide();
			$('#options').val("<?php  echo t('Sunday'); ?>\n<?php  echo t('Monday'); ?>\n<?php  echo t('Tuesday'); ?>\n<?php  echo t('Wednesday'); ?>\n<?php  echo t('Thursday'); ?>\n<?php  echo t('Friday'); ?>\n<?php  echo t('Saturday'); ?>");
			break;
		case 'months':
			$('#form-list-container').hide();
			$('#field-list-container').hide();
			$('#options-container').show();
			$('#numbers_form').hide();
			$('#options').val("<?php  echo t('January'); ?>\n<?php  echo t('February'); ?>\n<?php  echo t('March'); ?>\n<?php  echo t('April'); ?>\n<?php  echo t('May'); ?>\n<?php  echo t('June'); ?>\n<?php  echo t('July'); ?>\n<?php  echo t('August'); ?>\n<?php  echo t('September'); ?>\n<?php  echo t('October'); ?>\n<?php  echo t('November'); ?>\n<?php  echo t('December'); ?>");
			break;
		case 'states':
			$('#form-list-container').hide();
			$('#field-list-container').hide();
			$('#options-container').show();
			$('#numbers_form').hide();
			$('#options').val("Alabama\nAlaska\nArizona\nArkansas\nCalifornia\nColorado\nConnecticut\nDelaware\nFlorida\nGeorgia\nHawaii\nIdaho\nIllinois\nIndiana\nIowa\nKansas\nKentucky\nLouisiana\nMaine\nMaryland\nMassachusetts\nMichigan\nMinnesota\nMississippi\nMissouri\nMontana\nNebraska\nNevada\nNew Hampshire\nNew Jersey\nNew Mexico\nNew York\nNorth Carolina\nNorth Dakota\nOhio\nOklahoma\nOregon\nPennsylvania\nRhode Island\nSouth Carolina\nSouth Dakota\nTennessee\nTexas\nUtah\nVermont\nVirginia\nWashington\nWashington, D.C.\nWest Virginia\nWisconsin\nWyoming\n");
			break;
		case 'countries':
			$('#form-list-container').hide();
			$('#field-list-container').hide();
			$('#options-container').show();
			$('#numbers_form').hide();
			$('#options').val("Afghanistan\nAkrotiri\nAlbania\nAlgeria\nAmerican Samoa\nAndorra\nAngola\nAnguilla\nAntarctica\nAntigua and Barbuda\nArctic Ocean\nArgentina\nArmenia\nAruba\nAshmore and Cartier Islands\nAtlantic Ocean\nAustralia\nAustria\nAzerbaijan\nBahamas, The\nBahrain\nBaker Island\nBangladesh\nBarbados\nBelarus\nBelgium\nBelize\nBenin\nBermuda\nBhutan\nBolivia\nBosnia and Herzegovina\nBotswana\nBouvet Island\nBrazil\nBritish Indian Ocean Territory\nBritish Virgin Islands\nBrunei\nBulgaria\nBurkina Faso\nBurma\nBurundi\nCambodia\nCameroon\nCanada\nCape Verde\nCayman Islands\nCentral African Republic\nChad\nChile\nChina\nChristmas Island\nClipperton Island\nCocos (Keeling) Islands\nColombia\nComoros\nCongo, Democratic Republic of the\nCongo, Republic of the\nCook Islands\nCoral Sea Islands\nCosta Rica\nCote d'Ivoire\nCroatia\nCuba\nCyprus\nCzech Republic\nDenmark\nDhekelia\nDjibouti\nDominica\nDominican Republic\nEcuador\nEgypt\nEl Salvador\nEquatorial Guinea\nEritrea\nEstonia\nEthiopia\nFalkland Islands (Islas Malvinas)\nFaroe Islands\nFiji\nFinland\nFrance\nFrench Polynesia\nFrench Southern and Antarctic Lands\nGabon\nGambia, The\nGaza Strip\nGeorgia\nGermany\nGhana\nGibraltar\nGreece\nGreenland\nGrenada\nGuam\nGuatemala\nGuernsey\nGuinea\nGuinea-Bissau\nGuyana\nHaiti\nHeard Island and McDonald Islands\nHoly See (Vatican City)\nHonduras\nHong Kong\nHowland Island\nHungary\nIceland\nIndia\nIndian Ocean\nIndonesia\nIran\nIraq\nIreland\nIsle of Man\nIsrael\nItaly\nJamaica\nJan Mayen\nJapan\nJarvis Island\nJersey\nJohnston Atoll\nJordan\nKazakhstan\nKenya\nKingman Reef\nKiribati\nKorea, North\nKorea, South\nKuwait\nKyrgyzstan\nLaos\nLatvia\nLebanon\nLesotho\nLiberia\nLibya\nLiechtenstein\nLithuania\nLuxembourg\nMacau\nMacedonia\nMadagascar\nMalawi\nMalaysia\nMaldives\nMali\nMalta\nMarshall Islands\nMauritania\nMauritius\nMayotte\nMexico\nMicronesia, Federated States of\nMidway Islands\nMoldova\nMonaco\nMongolia\nMontenegro\nMontserrat\nMorocco\nMozambique\nNamibia\nNauru\nNavassa Island\nNepal\nNetherlands\nNetherlands Antilles\nNew Caledonia\nNew Zealand\nNicaragua\nNiger\nNigeria\nNiue\nNorfolk Island\nNorthern Mariana Islands\nNorway\nOman\nPacific Ocean\nPakistan\nPalau\nPalmyra Atoll\nPanama\nPapua New Guinea\nParacel Islands\nParaguay\nPeru\nPhilippines\nPitcairn Islands\nPoland\nPortugal\nPuerto Rico\nQatar\nRomania\nRussia\nRwanda\nSaint Barthelemy\nSaint Helena\nSaint Kitts and Nevis\nSaint Lucia\nSaint Martin\nSaint Pierre and Miquelon\nSaint Vincent and the Grenadines\nSamoa\nSan Marino\nSao Tome and Principe\nSaudi Arabia\nSenegal\nSerbia\nSeychelles\nSierra Leone\nSingapore\nSlovakia\nSlovenia\nSolomon Islands\nSomalia\nSouth Africa\nSouth Georgia and the South Sandwich Islands\nSouthern Ocean\nSpain\nSpratly Islands\nSri Lanka\nSudan\nSuriname\nSvalbard\nSwaziland\nSweden\nSwitzerland\nSyria\nTajikistan\nTanzania\nThailand\nTimor-Leste\nTogo\nTokelau\nTonga\nTrinidad and Tobago\nTunisia\nTurkey\nTurkmenistan\nTurks and Caicos Islands\nTuvalu\nUganda\nUkraine\nUnited Arab Emirates\nUnited Kingdom\nUnited States\nUnited States Pacific Island Wildlife Refuges\nUruguay\nUzbekistan\nVanuatu\nVenezuela\nVietnam\nVirgin Islands\nWake Island\nWallis and Futuna\nWest Bank\nWestern Sahara\nYemen\nZambia\nZimbabwe\nTaiwan\nEuropean Union");
			break;
		case 'numbers':
			$('#form-list-container').hide();
			$('#field-list-container').hide();
			$('#options-container').show();
			$('#numbers_form').show();
			$('#autofill_start').val("");
			$('#autofill_end').val("");
			break;
		case 'form-data':
			$('#form-list-container').show();
			$('#options-container').hide();
			$('#form-list').val(0);
			break;
		default:
			$('#form-list-container').hide();
			$('#field-list-container').hide();
			$('#options-container').show();
			$('#numbers_form').hide();
			$('#options').val('');
			break;
	}
	$('#autofill_options').val("");
}



function fill_numbers() {
	options = "";
	difference = $('#autofill_end').val() - $('#autofill_start').val();
	number_of_options = Math.abs(difference);
	if (number_of_options > 100) {
		proceed = confirm('<?php  echo t('The values you have entered result in over 100 options.  This may take some time to fill, and depending on the exact range of numbers you have specified, it could potentially crash your browser.  Click OK to continue if you still want to continue.'); ?>');
	} else {
		proceed = 1;
	}
	
	if (proceed) {
		if (difference >=0) {
			for (i=$('#autofill_start').val();i<=$('#autofill_end').val();i++) {
				options += i + "\n";
			}
		} else {
			for (i=$('#autofill_start').val();i>=$('#autofill_end').val();i--) {
				options += i + "\n";
			}
		}
	}
	$('#options').val(options);
	$('#numbers_form').hide();
}
</script>
<div class="ccm-ui">
<form id="newFieldForm" action="<?php  echo $formsURL; ?>" method="POST">
<input type="hidden" name="fID" value="<?php  echo intval($_GET['fID']); ?>" />
<input type="hidden" name="ffID" value="<?php  echo intval($_GET['ffID']); ?>" />
<input type="hidden" name="type" id="type" />
	
	<div id="tab-type" <?php  if(($_GET['ffID'] != '') || ($_GET['type'] != '')) { echo 'style="display:none;"'; } ?> >
		<div class="field-type-box">
			<div class="field-type-button" id="sem-text-single-line" title="Text (Single-line)"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/textfield.png" align="absmiddle" /> <?php  echo t('Text (Single-line)'); ?></div>
		</div>
		<div class="field-type-box">
			<div class="field-type-button" id="sem-text-multi-line" title="Text (Multi-line)"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/textarea.png" align="absmiddle"  /> <?php  echo t('Text (Multi-line)'); ?></div>
		</div>
		<div class="field-type-box">
			<div class="field-type-button" id="sem-number" title="Number"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/number.png" align="absmiddle"  /> <?php  echo t('Number'); ?></div>
		</div>
		<div class="field-type-box">
			<div class="field-type-button" id="sem-email-address" title="Email Address"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/email.png" align="absmiddle"  /> <?php  echo t('Email Address'); ?></div>
		</div>
		<div class="field-type-box">
			<div class="field-type-button" id="sem-dropdown" title="Dropdown"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/dropdown.png" align="absmiddle"  /> <?php  echo t('Dropdown'); ?></div>
		</div>
		<div class="field-type-box">
			<div class="field-type-button" id="sem-multi-select" title="Multi-Select"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/multi_select.png" align="absmiddle"  /> <?php  echo t('Multi-Select'); ?></div>
		</div>
		<div class="field-type-box">
			<div class="field-type-button" id="sem-radio-button" title="Radio Button"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/radio.png" align="absmiddle"  /> <?php  echo t('Radio Button'); ?></div>
		</div>
		<div class="field-type-box">
			<div class="field-type-button" id="sem-checkbox" title="Checkbox"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/checkbox.png" align="absmiddle"  /> <?php  echo t('Checkbox'); ?></div>
		</div>
		<div class="field-type-box">
			<div class="field-type-button" id="sem-true-false" title="True/False"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/true_false.png" align="absmiddle"  /> <?php  echo t('True/False'); ?></div>
		</div>
		<div class="field-type-box">
			<div class="field-type-button" id="sem-date" title="Date"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/date.png" align="absmiddle"  /> <?php  echo t('Date'); ?></div>
		</div>
		<div class="field-type-box">
			<div class="field-type-button" id="sem-time" title="Time"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/time.png" align="absmiddle"  /> <?php  echo t('Time'); ?></div>
		</div>
		<div class="field-type-box">
			<div class="field-type-button" id="sem-file-upload" title="File Upload"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/file_upload.png" align="absmiddle"  /> <?php  echo t('File Upload'); ?></div>
		</div>
		<div class="field-type-box">
			<div class="field-type-button" id="sem-file-from-file-manager" title="File from File Manager"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/file_manager.png" align="absmiddle"  /> <?php  echo t('File from File Manager'); ?></div>
		</div>
		<div class="field-type-box">
			<div class="field-type-button" id="sem-wysiwyg" title="WYSIWYG"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/wysiwyg.png" align="absmiddle"  /> <?php  echo t('WYSIWYG'); ?></div>
		</div>
		<div class="field-type-box">
			<div class="field-type-button" id="sem-sellable-item" title="Sellable Item"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/sellable_item.png" align="absmiddle"  /> <?php  echo t('Sellable Item'); ?></div>
		</div>
		<div class="field-type-box">
			<div class="field-type-button" id="sem-credit-card" title="Credit Card"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/credit_card.png" align="absmiddle"  /> <?php  echo t('Credit Card'); ?></div>
		</div>
		<div class="field-type-box">
			<div class="field-type-button" id="sem-hidden" title="Hidden"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/hidden.png" align="absmiddle" /> <?php  echo t('Hidden'); ?></div>
		</div>
		<div class="field-type-box">
			<div class="field-type-button" id="sem-section-divider" title="Section Divider"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/section_divider.png" align="absmiddle" /> <?php  echo t('Section Divider'); ?></div>
		</div>
		<div class="field-type-box">
			<div class="field-type-button" id="sem-next" title="Next Button"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/next.png" align="absmiddle" /> <?php  echo t('Next Button'); ?></div>
		</div>
		<div class="field-type-box">
			<div class="field-type-button" id="sem-previous" title="Previous Button"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/previous.png" align="absmiddle" /> <?php  echo t('Previous Button'); ?></div>
		</div>
		<div class="field-type-box">
			<div class="field-type-button" id="sem-text-no-user-input" title="Text (no user input)"><img src="<?php  echo DIR_REL; ?>/packages/sixeightforms/images/text.png" align="absmiddle"  /> <?php  echo t('Text (no user input)'); ?></div>
		</div>
	</div>
	
	
	
	<div id="tab-options" style="display:none">
		<h3 id="field-type-title"><a href="#"><?php  echo t('Field Label'); ?></a></h3>
		<hr />
		<ul class="nav-tabs tabs">
			<li class="active"><a href="#tab-general"><?php   echo t('General'); ?></a></li>
			<li><a href="#tab-format"><?php   echo t('Format'); ?></a></li>
			<li><a href="#tab-ecommerce"><?php   echo t('eCommerce'); ?></a></li>
			<li><a href="#tab-other"><?php   echo t('Other Settings'); ?></a></li>
		</ul>
	
		<div class="ccm-tab" id="tab-general">
			<div class="clearfix field-row" id="field-row-required">
				<label for="required"><?php  echo t('Is required?'); ?></label>
				<div class="input">
					<select name="required" id="required">
						<option value="">---</option>
						<option value="1"><?php  echo t('Yes'); ?></option>
						<option value="0"><?php  echo t('No'); ?></option>
					</select>
				</div>
			</div>
			<div class="clearfix field-row" id="field-row-label">
				<label for="input"><?php  echo t('Label'); ?></label>
				<div class="input">
					<input name="label" id="label" />
				</div>
			</div>
			<div class="clearfix field-row" id="field-row-text">
				<label for="text"><?php  echo t('Text'); ?></label>
				<div class="input">
					<textarea id="text" name="text" style="width:350px;height:60px;font-size:12px"></textarea>
				</div>
			</div>
			<div class="clearfix field-row" id="field-row-default-value">
				<label for="defaultValue"><?php  echo t('Default Value'); ?></label>
				<div class="input">
					<input name="defaultValue" id="defaultValue" />
				</div>
			</div>
			<div class="clearfix field-row" id="field-row-auto-populate">
				<label><?php  echo t('Auto-populate'); ?></label>
				<div class="input">
					<select id="auto-populate-option" onchange="populate_options(this.value);">
						<option value=""></option>
						<option value="days"><?php  echo t('Days of the Week'); ?></option>
						<option value="months"><?php  echo t('Months'); ?></option>
						<option value="states"><?php  echo t('U.S. States'); ?></option>
						<option value="countries"><?php  echo t('Countries'); ?></option>
						<option value="numbers"><?php  echo t('Numbers'); ?></option>
						<option value="form-data" <?php  if(strpos($options[0]['value'],'ffID:') !== false) { echo 'selected="selected"'; } ?>><?php  echo t('Form Record Data'); ?></option>
					</select>
					<p>
						<div id="numbers_form" class="help-block" style="display:none"><?php  echo t('Start:'); ?><input id="autofill_start" style="font-size:10px;width:25px" /> <?php  echo t('End:'); ?><input style="font-size:10px;width:25px" id="autofill_end" size="4"/> <a href="#" onclick="javascript:fill_numbers();"><?php  echo t('Fill'); ?></a></div>
					</p>
				</div>
			</div>
			<div class="clearfix field-row" id="field-row-options">
				<label><?php  echo t('Options'); ?></label>
				<div class="input">
					<div id="form-list-container" style="display:none">
						<p>
						<select id="form-list">
							<option value="0">Select a form</option>
							<?php  foreach($forms as $apForm) { ?>
								<option value="<?php  echo $apForm->fID; ?>" <?php  if($autopopulateFID == $apForm->fID) { echo 'selected="selected"'; } ?>><?php  echo $apForm->properties['name']; ?></option>
							<?php  } ?>
						</select>
						</p>
					</div>
					<div id="field-list-container" style="display:none">
						<p>
						<select id="field-list">
							<option value="0">Select a field</option>
						<?php 
						if(intval($autopopulateFID) > 0) {
							$autopopulateForm = sixeightform::getByID($autopopulateFID);
							foreach($autopopulateForm->getFields() as $apField) {
								if($field->ffID != $apField->ffID) {
							?>
							<option value="<?php  echo $apField->ffID; ?>" <?php  if($autopopulateFFID == $apField->ffID) { echo 'selected="selected"'; } ?>><?php  echo $apField->shortLabel; ?></option>
							<?php 
								}
							}
						} ?>
						</select>
						</p>
					</div>
					<div id="options-container">
						<textarea id="options" name="options" value="options" style="width:400px;height:100px" style="font-size:11px"><?php  echo $optionList; ?></textarea>	
					</div>
				</div>
			</div>
		</div>
		
		<div class="ccm-tab" id="tab-format" style="display:none">
			<div class="clearfix field-row" id="field-row-width">
				<label for="width"><?php  echo t('Field Width'); ?></label>
				<div class="input">
					<input name="width" id="width" class="tooltip-right" title="pixels" />
				</div>
			</div>
			<div class="clearfix field-row" id="field-row-height">
				<label for="height"><?php  echo t('Field Height'); ?></label>
				<div class="input">
					<input name="height" id="height" />
				</div>
			</div>
			<div class="clearfix field-row" id="field-row-max-input">
				<label for="maxLength" id="max-length-label"><?php  echo t('Maximum Input'); ?></label>
				<div class="input">
					<input name="maxLength" id="maxLength" class="tooltip-right" />
				</div>
			</div>
			<div class="clearfix field-row" id="field-row-format">
				<label for="format"><?php  echo t('Format'); ?></label>
				<div class="input">
					<select name="format" id="format">
						<option value="basic"><?php  echo t('Basic'); ?></option>
						<option value="simple"><?php  echo t('Simple'); ?></option>
						<option value="advanced"><?php  echo t('Advanced'); ?></option>
						<option value="office"><?php  echo t('Office'); ?></option>
					</select>
				</div>
			</div>
			<div class="clearfix field-row" id="field-row-toolbar">
				<label for="toolbar"><?php  echo t('Include C5 Toolbar?'); ?></label>
				<div class="input">
					<select name="toolbar" id="toolbar">
						<option value="0">---</option>
						<option value="1"><?php  echo t('Yes'); ?></option>
						<option value="0"><?php  echo t('No'); ?></option>
					</select>
				</div>
			</div>
			<div class="clearfix field-row" id="field-row-date-format">
				<label for="dateFormat"><?php  echo t('Date Format'); ?></label>
				<div class="input">
					<input name="dateFormat" id="dateFormat" /> <a href="http://docs.jquery.com/UI/Datepicker/formatDate" target="_blank"><?php  echo t('Formatting Options'); ?></a>
				</div>
			</div>
			<div class="clearfix field-row" id="field-row-min-year">
				<label for="minYear"><?php  echo t('Minimum Year'); ?></label>
				<div class="input">
					<input name="minYear" id="minYear" />
				</div>
			</div>
			<div class="clearfix field-row" id="field-row-max-year">
				<label for="maxYear"><?php  echo t('Maximum Year'); ?></label>
				<div class="input">
					<input name="maxYear" id="maxYear" />
				</div>
			</div>
			
			<div class="clearfix field-row" id="field-row-class">
				<label for="cssClass"><?php  echo t('Field CSS Class'); ?></label>
				<div class="input"><input name="cssClass" id="cssClass" /></div>
			</div>
			<div class="clearfix field-row" id="field-row-container-class">
				<label for="containerCssClass"><?php  echo t('Container CSS Class'); ?></label>
				<div class="input">
					<input name="containerCssClass" id="containerCssClass" />
				</div>
			</div>
		</div>
		
		<div class="ccm-tab" id="tab-ecommerce" style="display:none">
	        <?php  if($form->properties['gateway'] != '') { ?>
			<div class="clearfix field-row" id="field-row-ecommerce-name">
				<label for="eCommerceName"><?php  echo t('Send to payment gateway as'); ?></label>
				<div class="input">
	            	<?php  
	                $form->loadGatewayConfig();
					$gateway = new semGateway();
					$gatewayFields = $gateway->getFields();
					?>
	                <select name="eCommerceName" id="eCommerceName">
	                <option value="">---</option>
	                <?php  foreach ($gatewayFields as $key => $cf) { ?>
	                	<option value="<?php  echo $key; ?>"><?php  echo $cf['label']; ?></option>
	                <?php  } ?>
	                </select>
				</div>
			</div>
			<div class="clearfix field-row" id="field-row-grouping">
				<label for="groupWithPrevious"><?php  echo t('Group with Previous?'); ?></label>
				<div class="input">
					<select name="groupWithPrevious" id="groupWithPrevious">
						<option value="">---</option>
						<option value="1"><?php  echo t('Yes'); ?></option>
						<option value="0"><?php  echo t('No'); ?></option>
					</select>
				</div>
			</div>
			<div class="clearfix field-row" id="field-row-price">
				<label for="price"><?php  echo t('Price Per Item'); ?></label>
				<div class="input"><input name="price" id="price" /></div>
			</div>
			<div class="clearfix field-row" id="field-row-start-quantity">
				<label for="price"><?php  echo t('Start Quantity'); ?></label>
				<div class="input"><input name="qtyStart" id="qtyStart" /></div>
			</div>
			<div class="clearfix field-row" id="field-row-end-quantity">
				<label for="qtyEnd"><?php  echo t('End Quantity'); ?></label>
				<div class="input"><input name="qtyEnd" id="qtyEnd" /></div>
			</div>
			<div class="clearfix field-row" id="field-row-increment">
				<label for="qtyIncrement"><?php  echo t('Increment By'); ?></label>
				<div class="input"><input name="qtyIncrement" id="qtyIncrement" /></div>
			</div>
	        <?php  } else { ?>
	        	<h4><?php  echo t('Select a payment gateway in the form\'s settings to enable eCommerce.'); ?></h4>
	        <?php  } ?>
		</div>
		
		<div class="ccm-tab" id="tab-other" style="display:none">
		
			<div class="clearfix field-row" id="field-row-handle">
				<label for="handle"><?php  echo t('Field Handle'); ?></label>
				<div class="input"><input name="handle" id="handle" /></div>
			</div>
			
			<div class="clearfix field-row" id="field-row-expiration">
				<label for="isExpirationField"><?php  echo t('Use as expiration?'); ?></label>
				<div class="input">
					<select name="isExpirationField" id="isExpirationField">
						<option value="0"><?php  echo t('No'); ?></option>
						<option value="1"><?php  echo t('Yes'); ?></option>
					</select>
				</div>
			</div>
	        
	        <div class="clearfix field-row" id="field-row-indexable">
	        	<label for="indexable"><?php  echo t('Searchable?'); ?></label>
	        	<div class="input">
	        		<select name="indexable" id="indexable">
	        			<option value="0"><?php  echo t('No'); ?></option>
	        			<option value="1"><?php  echo t('Yes'); ?></option>
	        		</select>
	        		<div class="ccm-note"><?php  echo t('For Data Display integration'); ?></div>
	        	</div>
	        </div>
	        
	        <div class="clearfix field-row" id="field-row-unique">
	        	<label for="requireUnique"><?php  echo t('Validate as unique?'); ?></label>
	        	<div class="input">
	        		<select name="requireUnique" id="requireUnique">
	        			<option value="0"><?php  echo t('No'); ?></option>
	        			<option value="1"><?php  echo t('Yes'); ?></option>
	        		</select>
	        	</div>
	        </div>
	        
	        <div class="clearfix field-row" id="field-row-url-parameter">
				<label for="urlParameter"><?php  echo t('URL Parameter'); ?></label>
				<div class="input">
					<input name="urlParameter" id="urlParameter" class="tooltip-right" title="to set the value via URL" />
				</div>
			</div>
	        
	        <div class="clearfix field-row" id="field-row-populate-with">
				<label for="populateWith"><?php  echo t('Populate With'); ?></label>
				<div class="input">
					<select name="populateWith" id="populateWith">
	        			<option value=""></option>
	        			<option value="username">Username</option>
	        			<option value="email">User Email</option>
	        			<option value="uID">User ID</option>
	        			<?php  $userAttributes = UserAttributeKey::getList(); ?>
	        			<?php  foreach($userAttributes as $ak) { ?>
	        				<option value="<?php  echo $ak->getAttributeKeyHandle(); ?>"><?php  echo $ak->getAttributeKeyName(); ?></option>
	        			<?php  } ?>
	        		</select>
	        		<div class="ccm-note"><?php  echo t('Overrides default value'); ?></div>
	        	</div>
			</div>
			
			<div class="clearfix field-row" id="field-row-fileset">
				<label for="fsID"><?php  echo t('Fileset'); ?></label>
				<div class="input">
				<?php 
					Loader::model('file_set');
					$fileSet = new FileSet();
					$fileSets = $fileSet->find('fsType=' . FileSet::TYPE_PUBLIC);
					?>
					<select name="fsID" id="fsID">
						<option value="0">---</option>
					<?php  foreach($fileSets as $fs) { ?>
						<option value="<?php  echo $fs->getFileSetID(); ?>"><?php  echo $fs->getFileSetName(); ?></option>
					<?php  } ?>
					</select>
				</div>
			</div>
	        <div class="clearfix field-row" id="field-row-validate">
	        	<label for="validateSection"><?php  echo t('Validate section on click?'); ?></label>
	        	<div class="input">
	        		<select name="validateSection" id="validateSection">
	        			<option value="0"><?php  echo t('No'); ?></option>
	        			<option value="1"><?php  echo t('Yes'); ?></option>
	        		</select>
	        	</div>
	        </div>
	    </div>
	    
		<div class="dialog-buttons">
			<a href="javascript:void(0)" onclick="$.fn.dialog.closeTop();" class="btn ccm-button-left"><?php  echo t('Cancel'); ?></a>
			<a href="javascript:void(0)" onclick="createField();" class="btn ccm-button-right primary"><?php  echo t('Save'); ?></a>
		</div>
	</div>
</form>
</div>