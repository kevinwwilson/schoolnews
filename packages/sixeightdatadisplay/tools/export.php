<?php     
defined('C5_EXECUTE') or die(_("Access Denied."));
Loader::model('sixeightdatadisplay','sixeightdatadisplay');
Loader::model('form','sixeightforms');
Loader::model('form_style','sixeightforms');

header('Expires: 0');
header('Cache-control: private');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Description: File Transfer');
header('Content-Type: text/xml');
header('Content-disposition: attachment; filename=export-' . date("m-d-Y") . '.xml');

$xmlString = '<?php xml version="1.0" ?><data>';

if(count($_GET['forms']) > 0) {
	$xmlString .= '<forms>';
	foreach($_GET['forms'] as $fID) {
		$f = sixeightform::getByID($fID);
		$fXML = $f->getXML();
		$xmlString .= str_replace('<?php xml version="1.0"?>','',$fXML->asXML());
	}
	$xmlString .= '</forms>';
}

if(count($_GET['styles']) > 0) {
	$xmlString .= '<styles>';
	foreach($_GET['styles'] as $sID) {
		$s = sixeightformstyle::getByID($sID);
		$sXML = $s->getXML();
		$xmlString .= str_replace('<?php xml version="1.0"?>','',$sXML->asXML());
	}
	$xmlString .= '</styles>';
}

if(count($_GET['templates']) > 0) {
	$xmlString .= '<templates>';
	foreach($_GET['templates'] as $tID) {
		$tXML = sixeightdatadisplay::getXML($tID);
		$t = sixeightdatadisplay::getTemplate($tID);
		$xmlString .= str_replace('<?php xml version="1.0"?>','',$tXML->asXML());
	}
	$xmlString .= '</templates>';
}

$xmlString .= '</data>';

$xml = new SimpleXMLElement($xmlString);
echo formatXmlString($xml->asXML());

function formatXmlString($xml) {  
  
  // add marker linefeeds to aid the pretty-tokeniser (adds a linefeed between all tag-end boundaries)
  $xml = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $xml);
  
  // now indent the tags
  $token      = strtok($xml, "\n");
  $result     = ''; // holds formatted version as it is built
  $pad        = 0; // initial indent
  $matches    = array(); // returns from preg_matches()
  
  // scan each line and adjust indent based on opening/closing tags
  while ($token !== false) : 
  
    // test for the various tag states
    
    // 1. open and closing tags on same line - no change
    if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) : 
      $indent=0;
    // 2. closing tag - outdent now
    elseif (preg_match('/^<\/\w/', $token, $matches)) :
      $pad--;
    // 3. opening tag - don't pad this one, only subsequent tags
    elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) :
      $indent=1;
    // 4. no indentation needed
    else :
      $indent = 0; 
    endif;
    
    // pad the line with the required number of leading spaces
    $line    = str_pad($token, strlen($token)+$pad, '     ', STR_PAD_LEFT);
    $result .= $line . "\n"; // add to the cumulative result, with linefeed
    $token   = strtok("\n"); // get the next token
    $pad    += $indent; // update the pad size for subsequent lines    
  endwhile; 
  
  return $result;
}