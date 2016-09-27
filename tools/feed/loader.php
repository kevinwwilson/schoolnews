<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
$type = $_REQUEST['type'];
$name = $_REQUEST['name'];

$stack = Stack::getByName('Sponsors');
$blocks = $stack->getBlocks();
// var_dump($blocks);
ob_start();
foreach ($blocks as $block) {
    $block->display();
}
$html = ob_get_clean();
$returnData = ['status' => 'Success', 'data' => $html];
$json = json_encode($returnData);

header('Content-Type: application/javascript');
echo $json;
