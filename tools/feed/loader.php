<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
$type = strtolower($_REQUEST['type']);
$name = $_REQUEST['name'];
$error = false;

//get custom php generated content
if ($type == 'custom') {
    ob_start();
    $result = include 'custom\\'. $name . '.php';
    if ($result) {
        $html = ob_get_clean();
    } else {
        $html = '<p class="error"> Unknown feed name: ' . $name . '</p>';
        $error = true;
    }
    //assign all captured output to variable
} elseif ($type == 'stack') {
    //get stack
    $stack = Stack::getByName($name);
    if ($stack) {
        $blocks = $stack->getBlocks();
        //start capturing output stream
        ob_start();
        foreach ($blocks as $block) {
            $block->display();
        }
        //assign all captured output to variable
        $html = ob_get_clean();
    } else {
        $html = '<p class="error"> Unknown stack name: ' . $name . '</p>';
        $error = true;
    }

} else {
    $html = '<p class="error"> Expected feed type of custom or stack and found ' . $type . '</p>';
    $error = true;
}

//keep the images from loading when first brought into the browsers
$html = str_replace('src="', 'src-data="', $html);

$returnData = [];
if ($error) {
    $returnData['status'] = 'Error';
} else {
    $returnData['status'] = 'Success';
}

$returnData['data'] = $html;
$json = json_encode($returnData);

header('Content-Type: application/javascript');
echo $_GET['callback']."(";
echo $json;
echo");";
