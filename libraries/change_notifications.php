<?php defined('C5_EXECUTE') or die("Access Denied.");

$publishHelper = Loader::helper('news_publish');
if ($publishHelper->groupReadyToPublish()) {
    $publishHelper->publishGroup();
} 