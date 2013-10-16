<?php   
defined('C5_EXECUTE') or die(_("Access Denied."));
Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');
$as = sixeightAnswerSet::getByIDAndEditCode(intval($_GET['asID']),$_GET['editCode']);
$as->delete($_GET['deletePage']);

$c = Page::getByID(intval($_GET['cID']));

header('location:' . View::url($c->getCollectionPath()));

