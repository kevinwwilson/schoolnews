<?php   
defined('C5_EXECUTE') or die(_("Access Denied.")); 

Loader::model('sixeightdatadisplay','sixeightdatadisplay');
Loader::model('form','sixeightforms');
Loader::model('field','sixeightforms');
Loader::model('answer_set','sixeightforms');
Loader::model('answer_set_list','sixeightforms');

class SixeightdatadisplayBlockController extends BlockController {

	protected $btTable = 'btSixeightdatadisplay';
	protected $btInterfaceWidth = "450";
	protected $btInterfaceHeight = "380";

	public function getBlockTypeDescription() {
		return t("Display data from advanced forms block.");
	}
	
	public function getBlockTypeName() {
		return t("Data Display");
	}
	
	public function add() {
		$this->edit();
	}
	
	public function edit() {
		$forms = sixeightForm::getAll();
		$listTemplates = sixeightdatadisplay::getTemplates('list');
		$detailTemplates = sixeightdatadisplay::getTemplates('detail');
		
		if((!count($forms)) || (!count($listTemplates)) || (!count($detailTemplates))) {
			$this->set('isReady',false);
			$this->set('formCount',count($forms));
			$this->set('listTemplateCount',count($listTemplates));
			$this->set('detailTemplateCount',count($detailTemplates));
		} else {
			$this->set('isReady',true);
			$this->set('forms',$forms);
			$this->set('listTemplates',$listTemplates);
			$this->set('detailTemplates',$detailTemplates);
		}
	}
	
	function on_page_view() {
		$html = Loader::helper('html');
		$f = sixeightForm::getByID($this->fID);
		$fBlock = BlockType::getByHandle('sixeightforms');
		if(($f->userCanEdit()) || ($f->ownerCanEdit()) || ($_GET['ddMode'] == 'editRecord')) {
			$fBlock->controller->loadHeaderItems($this->fID);
			$this->addHeaderItem($html->css('jquery.ui.css'));
			$this->addHeaderItem($html->javascript('jquery.ui.js'));
			$this->addFooterItem($html->javascript('frontend-actions.js','sixeightdatadisplay'));
		}
	}
	
	function view() {
		global $c;
		if(!$c) {
			$c = Page::getByID(1);
		}
		
		$bt = BlockType::getByHandle('sixeightdatadisplay');
		$this->set('bt',$bt);
		
		//Set the form and the fields
		$f = sixeightForm::getByID($this->fID);
		$this->set('f',$f);
		$fields = $f->getFields();
		$this->set('formFields',$fields);
		
		if($_GET['ddMode'] != 'editRecord') {
		
			//Determine whether to show list or detail
			if($_GET['dd_asId']=='') { //If an answer set ID has not been passed via the URL
				$displayList = 1;
				$this->set('displayList',1);	
			} elseif (intval($_GET['bID']) == '0') { //If the block ID passed from the URL is set to zero
				$displayList = 0;
				$this->set('displayList',0);	
			} elseif ($_GET['bID'] != $this->bID) { //If the block ID that has been passed is not equal to the ID of this block
				$displayList = 1;
				$this->set('displayList',1);
			}
			
			if ($_GET['q'] != '') { //If a search query has been passed
				$this->set('noResultsLabel',t('Your search - <b>' . htmlspecialchars($_GET['q']) . '</b> - did not match any items.'));
			}
			
			//Set the list template
			$listTemplate = sixeightdatadisplay::getTemplate($this->listTemplateID);
			$this->set('listTemplate',$listTemplate);
			
			//Determine whether or not the alternate content template exists
			if (trim($listTemplate['templateAlternateContent']) != '') {
				$this->set('alternateExists',true);
			}
			
			//Set the detail template
			$detailTemplate = sixeightdatadisplay::getTemplate($this->detailTemplateID);
			$this->set('detailTemplateContent',$detailTemplate['templateContent']);
			$this->set('fields',$f->getFields());
			
			//Determine whether details should be displayed on this page or on a different page (as specified in the block's settings)
			if ($this->detailsInline == 1) {
				$detailPageID = $c->getCollectionId();
			} else {
				$detailPageID = $this->detailPage;
			}
			
			//Set the detail page ID and object
			$detailPageObj = Page::getByID($detailPageID);
			$this->set('detailPageId',$detailPageID);
			$this->set('detailPageObj',$detailPageObj);
			
			if ($_GET['sortBy'] != '') { //If sort by is passed in the URL
				$sortBy = intval($_GET['sortBy']);
			} elseif ($this->sortBy != '') { //If sort by is set in the block's settings
				$sortBy = $this->sortBy;
			}
			
			if ($_GET['sortOrder'] == '') { //If sort order is passed in the URL
				$sortOrder=$this->sortOrder;
			} else {
				if($_GET['sortOrder'] == 'ASC') {
					$sortOrder = 'ASC';
				} else {
					$sortOrder = 'DESC';
				}
			}
			
			if ($_GET['ccm_paging_p'] == '') {
				$pageNum = 1;
			} else {
				$pageNum = intval($_GET['ccm_paging_p']);
			}
	
			if ($_GET['q'] != '') {
				$q = $_GET['q'];
			}
			
			
			if($displayList == 1) {
				//Get the actual answer sets
				$asl = sixeightAnswerSetList::get($this->fID);
				$fullasl = sixeightAnswerSetList::get($this->fID);
				//Set the appropriate answer set list properties
				if($this->approvedOnly) {
					$asl->requireApproval();
					$fullasl->requireApproval();
				}
				
				if($q) {
					$asl->setSearchQuery($q);
					$fullasl->setSearchQuery($q);
				}
				
				if($sortOrder) {
					$asl->setSortOrder($sortOrder);
					$fullasl->setSortOrder($sortOrder);
				}
				
				if($sortBy) {
					$asl->setSortField($sortBy);
					$fullasl->setSortField($sortBy);
				}
				
				if($pageNum) {
					$asl->setPageNum($pageNum);
				}
				
				if($this->itemsPerPage) {
					$asl->setPageSize($this->itemsPerPage);
				} else {
					$asl->setPageSize(0);
				}
				
				if($this->showExpiredRecords) {
					$asl->includeExpired();
					$fullasl->includeExpired();
				}
				
				if($this->showOwnedRecords) {
					$asl->requireOwnership();
					$fullasl->requireOwnership();
				}
				
				$filterFields = explode(',',$this->defaultFilterField);
				$filterValues = explode(',',$this->defaultFilterValue);
				
				$i = 0;
				if(count($filterFields) > 0) {
					foreach($filterFields as $filterField) {
						if(intval($filterField) != 0) {
							$asl->addFilter($filterField,$filterValues[$i]);
							$fullasl->addFilter($filterField,$filterValues[$i]);
						}
						$i++;
					}
				}
				
				$answerSets = $asl->getAnswerSets();
				
				if($this->displayPaginator==1) {
					$fullasl->setPageNum(1);
					$fullasl->setPageSize(0);
					$answerSetCount = $fullasl->getAnswerSetCount();
				}
				
				echo '<!--ASC:' . $answerSetCount . '-->';
				
			} else {
				//Get the answer set to be display in the detail template
				$answerSets[intval($_GET['dd_asId'])] = sixeightAnswerSet::getByID(intval($_GET['dd_asId']));
			}
	
			$this->set('answerSets',$answerSets);
			
			$pageBase=DIR_REL.'/index.php?cID='.$c->getCollectionID();
			$pageBase=$pageBase . '&q=' . htmlentities($_GET['q']) . '&sortBy=' . intval($sortBy) . '&sortOrder=' . $sortOrder;
			$this->set('paginator',$this->createPagination($pageBase,$answerSetCount,$this->itemsPerPage,intval($pageNum)));
			$this->set('pageBase',DIR_REL . '/index.php?cID='.$c->getCollectionID());
			$this->set('pageParams','&q=' . htmlentities($_GET['q']) . '&sortBy=' . intval($sortBy) . '&sortOrder=' . $sortOrder . '&ccm_paging_p=' . intval($pageNum));
			
			//Set the search box label
			if ($_GET['q'] == '') {
				if($this->searchPlaceholder == '') {
					$this->set('searchPlaceholder',t('Search'));
				}
			} else {
				$this->set('searchPlaceholder',t($_GET['q']));
			}
			
			if($this->searchButtonText == '') {
				$this->set('searchButtonText',t('Go'));
			} else {
				$this->set('searchButtonText',$this->searchButtonText);
			}
			
			if($this->searchResetButtonText == '') {
				$this->set('searchResetButtonText',t('Reset'));
			} else {
				$this->set('searchResetButtonText',$this->searchResetButtonText);
			}
			
			if($this->sortLabel == '') {
				$this->set('sortLabel',t('Sort by'));
			} else {
				$this->set('sortLabel',$this->sortLabel);
			}
			
			if($this->sortButtonLabel == '') {
				$this->set('sortButtonLabel',t('Sort'));
			} else {
				$this->set('sortButtonLabel',$this->sortButtonLabel);
			}
		} //End check for $_GET['ddMode']
	}
	
	function save($data) {
		$args=$data;
		if ($data['showExpiredRecords'] == '1') {
			$args['showExpiredRecords'] = '1';
		} else {
			$args['showExpiredRecords'] = '0';
		}
		
		if ($data['approvedOnly'] == '1') {
			$args['approvedOnly'] = '1';
		} else {
			$args['approvedOnly'] = '0';
		}
		
		if ($data['showOwnedRecords'] == '1') {
			$args['showOwnedRecords'] = '1';
		} else {
			$args['showOwnedRecords'] = '0';
		}
		
		if ($data['itemsPerPage'] == '') {
			$args['itemsPerPage'] = '0';
		}
		
		if ($data['displayPaginator'] == '1') {
			$args['displayPaginator'] = '1';
		} else {
			$args['displayPaginator'] = '0';
		}
		
		if ($data['detailsInline'] == '1') {
			$args['detailsInline'] = '1';
		} else {
			$args['detailsInline'] = '0';
		}
		
		if ($data['enableSearch'] == '1') {
			$args['enableSearch'] = '1';
		} else {
			$args['enableSearch'] = '0';
		}
		
		if ($data['enableSearchReset'] == '1') {
			$args['enableSearchReset'] = '1';
		} else {
			$args['enableSearchReset'] = '0';
		}
		
		if(is_array($args['searchableFields'])) {
			$args['searchableFields']='';
			foreach ($data['searchableFields'] as $qID) {
				$args['searchableFields'] .= $qID . ',';
			}
		}
					
		if ($data['sortBy'] == '') {
			$args['sortBy'] = '0';
		}
		
		if ($data['enableUserSort'] == '1') {
			$args['enableUserSort'] = '1';
		} else {
			$args['enableUserSort'] = '0';
		}
		
		if(is_array($args['sortableFields'])) {
			$args['sortableFields']='';
			foreach ($data['sortableFields'] as $qID) {
				$args['sortableFields'] .= $qID . ',';
			}
		}
		
		if(count($data['filterField']) > 0) {
			$i = 0;
			$args['defaultFilterField'] = '';
			$args['defaultFilterValue'] = '';
			foreach($data['filterField'] as $filterField) {
				if(intval($filterField) != 0) {
					$args['defaultFilterField'] .= $filterField . ',';
					$args['defaultFilterValue'] .= $data['filterValue'][$i] . ',';
				}
				$i++;
			}
			
			$args['defaultFilterField'] = (string)$args['defaultFilterField'];
			$args['defaultFilterValue'] = (string)$args['defaultFilterValue'];
		}
		
		parent::save($args);
	}

	function fieldIsSortable($ffID,$sortableFields) {
		foreach(explode(',',$sortableFields) as $sortableqID) {
			if($sortableqID == $ffID) {
				return true;
			}
		}
		return false;
	}
	
	function createPagination($pageBase,$numItems,$itemsPerPage,$currentPage) {
		$paginator=Loader::helper('pagination');
		if(intval($itemsPerPage) == 0) {
			$paginatorPageSize = 100000000;
		} else {
			$paginatorPageSize = $itemsPerPage;
		}
		$paginator->init(intval($currentPage),$numItems,$pageBase,$paginatorPageSize);
		return $paginator;
	}
	
	/*********
	This is the main function in the template
	**********/
	function generateTemplateContent($template,$fields,$answerSet) {
		global $c;
		$uh = Loader::helper('concrete/urls');
		
		$f = sixeightform::getByID($answerSet->fID);
		
		$asID = $answerSet->asID;
		$recordID = $answerSet->recordID;
		
		$rowTemplateDOM = new DOMDocument();
		if(@$rowTemplateDOM->loadXML('<?php xml version="1.0"?><root>' . str_replace('&','&amp;',$template) . '</root>')) {
			unset($placeholders);
			$elements = $rowTemplateDOM->getElementsByTagName('*');
			foreach($elements as $element) {
				switch($element->tagName) {
					case 'timestamp':
						$dateFormat = $element->getAttribute('format');
						if($dateFormat == '') {
							$dateFormat = 'F j, Y';
						}
						$answerDate = date($dateFormat,$answerSet->dateSubmitted);
						$varName = $element->getAttribute('placeholder');
						if($varName != '') {
							$placeholders[] = array('placeholder' => '{{' . $varName . '}}', 'value' => $answerDate);
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => '');
						} else {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $answerDate);
						}
						break;
	  					$varName = $element->getAttribute('placeholder');
						if($varName != '') { 
							$placeholders[] = array('placeholder' => '{{' . $varName . '}}', 'value' => $asID);
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => '');
						} else {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $asID);
						}
						break;
					case 'owner':
						if(intval($answerSet->creator) > 0) {
							$owner = User::getByUserID($answerSet->creator);
							$ownerInfo = UserInfo::getByID($answerSet->creator);
							if($element->getAttribute('attribute') == '') {
								$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $owner->getUserName());
							} else {
								switch($element->getAttribute('attribute')) {
									case 'email':
										$value = $ownerInfo->getUserEmail();
										break;
									case 'id':
										$value = $answerSet->creator;
										break;
									default:
										if($ownerInfo) {
											$value = $ownerInfo->getAttribute($element->getAttribute('attribute'));
										}
								}
							}
						} else {
							$value = '';
						}
						
						$varName = $element->getAttribute('placeholder');
						if($varName != '') {
							$placeholders[] = array('placeholder' => '{{' . $varName . '}}', 'value' => $value);
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => '');
						} else {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $value);
						}
						break;
					case 'answerid':
						$varName = $element->getAttribute('placeholder');
						if($varName != '') {
							$placeholders[] = array('placeholder' => '{{' . $varName . '}}', 'value' => $recordID);
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => '');
						} else {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $recordID);
						}
						break;
					case 'recordid':
						$varName = $element->getAttribute('placeholder');
						if($varName != '') {
							$placeholders[] = array('placeholder' => '{{' . $varName . '}}', 'value' => $recordID);
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => '');
						} else {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $recordID);
						}
						break;
					case 'amountcharged':
						$varName = $element->getAttribute('placeholder');
						if($varName != '') {
							$placeholders[] = array('placeholder' => '{{' . $varName . '}}', 'value' => $answerSet->amountCharged);
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => '');
						} else {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $answerSet->amountCharged);
						}
						break;
					case 'amountpaid':
						$varName = $element->getAttribute('placeholder');
						if($varName != '') {
							$placeholders[] = array('placeholder' => '{{' . $varName . '}}', 'value' => $answerSet->amountPaid);
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => '');
						} else {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $answerSet->amountPaid);
						}
						break;
					case 'edit':
						if($answerSet->userCanEdit()) {
							if($element->getAttribute('display') == 'inline') {
								$link = DIR_REL . '/index.php?cID=' . $c->getCollectionID() . '&asID=' . $asID . '&editCode=' . $answerSet->editCode . '&ddMode=editRecord';
							} else {
								$link = $uh->getToolsURL('edit_answerset','sixeightforms') . '?asID=' . $asID . '&editCode=' . $answerSet->editCode;
								$class = 'edit-answer-link';
							}
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element),'value'=>'<a href="' . $link . '" class="' . $class . '">' . t('Edit') . '</a>');
						} else {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element),'value'=>'');
						}
						break;
					case 'delete':
						if($answerSet->userCanDelete()) {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element),'value'=>'<a href="' . $uh->getToolsURL('delete_answerset_prompt','sixeightforms') . '?asID=' . $asID . '&editCode=' . $answerSet->editCode . '&cID=' . $c->getCollectionID() . '" class="delete-answer-link">' . t('Delete') . '</a>');
						} else {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element),'value'=>'');
						}
						break;
					case 'approve';
						if($f->properties['sendApprovalNotification'] == 1) {
							if($asUI = UserInfo::getByID($answerSet->creator)) {
								$asEmail = $asUI->getUserEmail();
							}
						} else {
							$asEmail = '';
						}
						
						if($f->userCanApprove()) {
							if($answerSet->isApproved) {
								$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element),'value'=>'<a name="' . $asEmail. '" rel="' . $asID . '" href="#" class="approve-answer-link">' . t('Unapprove') . '</a>');
							} else {
								$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element),'value'=>'<a name="' . $asEmail. '" rel="' . $asID . '" href="#" class="approve-answer-link">' . t('Approve') . '</a>');
							}
						} else {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element),'value'=>'');
						}
						break;
					case 'field':
						$maxLength = $element->getAttribute('maxlength');
						$fileProperty = $element->getAttribute('property');
						$format = $element->getAttribute('format');
						if($maxLength == '') {
							if($this->getAnswerValueByFieldName($fields,$element->getAttribute('name'),$answerSet,$fileProperty) == '') {
								$answerValue = $element->getAttribute('default');
							} elseif($format == 'number') {
								$answerValue = number_format($this->getAnswerValueByFieldName($fields,$element->getAttribute('name'),$answerSet,$fileProperty));
							} elseif ($format == 'time') {
								$originalAnswerValue = $this->getAnswerValueByFieldName($fields,$element->getAttribute('name'),$answerSet,$fileProperty);
								$parts = explode("\r\n",$originalAnswerValue);
								$answerValue = $parts[0] . ':' . $parts[1] . ' ' . $parts[2];
							} elseif ($format == 'money') {
								$answerValue = number_format($this->getAnswerValueByFieldName($fields,$element->getAttribute('name'),$answerSet,$fileProperty),2);
							} elseif ($format != '') {
								$dateFormat = $element->getAttribute('format');
								$answerValue = $this->getAnswerValueByFieldName($fields,$element->getAttribute('name'),$answerSet,$fileProperty);
								$answerValue = date($dateFormat,strtotime($answerValue));
							} else {
								$answerValue = $this->getAnswerValueByFieldName($fields,$element->getAttribute('name'),$answerSet,$fileProperty,$element->getAttribute('width'),$element->getAttribute('height'));
							}
							
							if($element->getAttribute('separator') != '') {
								$answerParts = explode("\r\n",$answerValue);
								$answerValue = '';
								if(count($answerParts) > 0) {
									$i = 0;
									foreach($answerParts as $ap) {
										if($i == 0) {
											$answerValue .= $ap;
										} else {
											$answerValue .= html_entity_decode($element->getAttribute('separator')) . $ap;
										}
										$i++;
									}
								}
							}
						} else {
							$answerValue = $this->shortenText($this->getAnswerValueByFieldName($fields,$element->getAttribute('name'),$answerSet,$fileProperty),$maxLength);
						}
						
						if($element->getAttribute('html') == 'true') {
							$answerValue = html_entity_decode($answerValue);
						}
						
						if($element->getAttribute('striptags') == 'true') {
							if($element->getAttribute('allowedtags') == '') {
								$answerValue = strip_tags($answerValue);
							} else {
								$allowedTags = '';
								$tags = explode(',',trim($element->getAttribute('allowedtags')));
								foreach($tags as $tag) {
									$allowedTags .= '<' . $tag . '>';
								}
								$answerValue = strip_tags($answerValue,$allowedTags);
							}
						}
						
						$varName = $element->getAttribute('placeholder');
						if($varName != '') {
							$placeholders[] = array('placeholder' => '{{' . $varName . '}}', 'value' => $answerValue);
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => '');
						} else {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $answerValue);
						}
						break;
				}
			}
			
			
			$template = $rowTemplateDOM->saveXML();
			if(count($placeholders) > 0) {
				foreach($placeholders as $ph) {
					$template = str_replace($ph['placeholder'],$ph['value'],$template);
				}
			}
	
			unset($placeholders); //Unsetting this because we're about to use it with our if statements
	
			//Recreate the DOM Document so that we can process the if statements.  We have to create, then export it (using saveXML) and then re-import, to make sure that empty tags will match up correctly when replacing if statements.
			$rowTemplateDOM = new DOMDocument();
			$rowTemplateDOM->loadXML(str_replace('&','&amp;',$template));
			$template = $rowTemplateDOM->saveXML();
			//One more time, to make sure empty tags are appropriately converted to self-closing tags
			$rowTemplateDom = new DOMDocument();
			$rowTemplateDOM->loadXML($template);
			
			//Process <switch /> elements
			$switchElements = $rowTemplateDOM->getElementsByTagName('switch');
			foreach($switchElements as $switch) {
				$fieldName = $switch->getAttribute('name');
				$fieldValue = $this->getAnswerValueByFieldName($fields,$fieldName,$answerSet);
				$cases = $switch->getElementsByTagName('case');
				$caseFound = 0;
				foreach($cases as $case) {
					if($fieldValue == $case->getAttribute('value')) {
						$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($switch), 'value' => $this->getInnerHTML($rowTemplateDOM,$case));
						$caseFound++;
					}
				}
				if($caseFound == 0) {
					$default = $switch->getElementsByTagName('default')->item(0);
					$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($switch), 'value' => $this->getInnerHTML($rowTemplateDOM,$default));
				} else {
					$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($switch), 'value' => '');
				}
			}
			
			//Process <if /> elements
			$ifElements = $rowTemplateDOM->getElementsByTagName('if');
			foreach($ifElements as $element) {
				$fieldName = $element->getAttribute('name');
				$fieldValue = $this->getAnswerValueByFieldName($fields,$fieldName,$answerSet);
				$comparison = $element->getAttribute('comparison');
				$comparisonValue = $element->getAttribute('value');
				$status = $element->getAttribute('status');
				if($status == 'approved') {
					if($answerSet->isApproved == 1) {
						$else = $element->getElementsByTagName('else')->item(0);
						if($else) {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($else), 'value' => '');
							$element->removeChild($else);
						}
						$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$element));
					} else {
						$else = $element->getElementsByTagName('else')->item(0);
						if($else) {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$else));
						} else {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => '');
						}
						$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$else));
					}
				} elseif($status == 'not approved') {
					if($answerSet->isApproved == 2) {
						$else = $element->getElementsByTagName('else')->item(0);
						if($else) {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($else), 'value' => '');
							$element->removeChild($else);
						}
						$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$element));
					} else {
						$else = $element->getElementsByTagName('else')->item(0);
						if($else) {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$else));
						} else {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => '');
						}
						$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$else));
					}
				} elseif($status == 'pending') {
					if($answerSet->isApproved == 0) {
						$else = $element->getElementsByTagName('else')->item(0);
						if($else) {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($else), 'value' => '');
							$element->removeChild($else);
						}
						$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$element));
					} else {
						$else = $element->getElementsByTagName('else')->item(0);
						if($else) {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$else));
						} else {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => '');
						}
						$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$else));
					}
				} elseif($comparison == '') {
					if($fieldValue != '') {
						$else = $element->getElementsByTagName('else')->item(0);
						if($else) {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($else), 'value' => '');
							$element->removeChild($else);
						}
						$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$element));
					} else {
						$else = $element->getElementsByTagName('else')->item(0);
						if($else) {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$else));
						} else {
							$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => '');
						}
						$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$else));
					}
				} else {
					switch($comparison) {
						case 'equal to':
							if ($fieldValue == $comparisonValue) {
								$else = $element->getElementsByTagName('else')->item(0);
								if($else) {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($else), 'value' => '');
									$element->removeChild($else);
								}
								$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$element));
							} else {
								$else = $element->getElementsByTagName('else')->item(0);
								if($else) {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$else));
								} else {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => '');
								}
							}
							break;
						case 'not equal to':
							if ($fieldValue != $comparisonValue) {
								$else = $element->getElementsByTagName('else')->item(0);
								if($else) {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($else), 'value' => '');
									$element->removeChild($else);
								}
								$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$element));
							} else {
								$else = $element->getElementsByTagName('else')->item(0);
								if($else) {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$else));
								} else {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => '');
								}
							}
							break;
						case 'greater than':
							if ($fieldValue > $comparisonValue) {
								$else = $element->getElementsByTagName('else')->item(0);
								if($else) {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($else), 'value' => '');
									$element->removeChild($else);
								}
								$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$element));
							} else {
								$else = $element->getElementsByTagName('else')->item(0);
								if($else) {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$else));
								} else {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => '');
								}
							}
							break;
						case 'greater than or equal to':
							if ($fieldValue >= $comparisonValue) {
								$else = $element->getElementsByTagName('else')->item(0);
								if($else) {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($else), 'value' => '');
									$element->removeChild($else);
								}
								$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$element));
							} else {
								$else = $element->getElementsByTagName('else')->item(0);
								if($else) {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$else));
								} else {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => '');
								}
							}
							break;
						case 'less than':
							if ($fieldValue < $comparisonValue) {
								$else = $element->getElementsByTagName('else')->item(0);
								if($else) {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($else), 'value' => '');
									$element->removeChild($else);
								}
								$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$element));
							} else {
								$else = $element->getElementsByTagName('else')->item(0);
								if($else) {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$else));
								} else {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => '');
								}
							}
							break;
						case 'less than or equal to':
							if ($fieldValue <= $comparisonValue) {
								$else = $element->getElementsByTagName('else')->item(0);
								if($else) {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($else), 'value' => '');
									$element->removeChild($else);
								}
								$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$element));
							} else {
								$else = $element->getElementsByTagName('else')->item(0);
								if($else) {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$else));
								} else {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => '');
								}
							}
							break;
						case 'contains':
							if (stristr($fieldValue,$comparisonValue)) {
								$else = $element->getElementsByTagName('else')->item(0);
								if($else) {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($else), 'value' => '');
									$element->removeChild($else);
								}
								$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$element));
							} else {
								$else = $element->getElementsByTagName('else')->item(0);
								if($else) {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$else));
								} else {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => '');
								}
							}
							break;
						case 'does not contain':
							if (!stristr($fieldValue,$comparisonValue)) {
								$else = $element->getElementsByTagName('else')->item(0);
								if($else) {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($else), 'value' => '');
									$element->removeChild($else);
								}
								$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$element));
							} else {
								$else = $element->getElementsByTagName('else')->item(0);
								if($else) {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => $this->getInnerHTML($rowTemplateDOM,$else));
								} else {
									$placeholders[] = array('placeholder' => $rowTemplateDOM->saveXML($element), 'value' => '');
								}
							}
							break;
					}
				}
			}
			
			//Now we're replacing just the if statements
			if(count($placeholders) > 0) {
				foreach($placeholders as $ph) {
					$template = str_replace($ph['placeholder'],$ph['value'],$template);
				}
			}
		} //End if(@$rowTemplateDOM->loadXML)
		
		$template = $this->xml2xhtml($template);

		return str_replace('&amp;','&',$template);
	}
	
	function shortenText($string, $length){
		$string = strip_tags($string);
		$lengthParts = explode(' ',$length);
		$length = $lengthParts[0];
		$format = $lengthParts[1];
		settype($string, 'string');
		settype($length, 'integer');
		
		if($format == 'words') {
			$length--;
			$words = explode(' ',$string);
			if(count($words) > $length) {
				$i=0;
				$j=0;
				while($i<$length) {
					if(trim($words[$j]) != '') {
						$output .= trim($words[$j]) . ' ';
						$i++;
					}
					$j++;
				}
				$lastWordCount = 0;
				while($lastWordCount < 1) {
					if(trim($words[$j]) != '') {
						$output .= trim($words[$j]);
						$lastWordCount++;
					}
					$j++;
				}
			} else {
				$output = $string;
			}
		} else {
			for($a = 0; $a < $length AND $a < strlen($string); $a++){
				$output .= $string[$a];
			}
		}
		return $output;
	}
    
	public function getInnerHTML(&$dom, &$node, $html = false) {
		// Thanks to Bobby Whitman for this handy function
		// http://www.dynamit.us/blog/2009/03/php-dom-innerhtml-method/
		// if html parameter not specified, return the current contents of $node
		$doc = new DOMDocument();
		if($node) {
			foreach ($node->childNodes as $child) {
				$doc->appendChild($doc->importNode($child, true));
			}
		}
		return $doc->saveHTML();
	}
	
	public function getAnswerValueFromAnswerSet($answerSet,$question,$property='') {
		if($question->type=='Text (Multi-line)') {
			$answerValue = nl2br(str_replace('&','&amp;',$answerSet->answers[$question->ffID]['value']));
		} elseif(($question->type=='File from File Manager') || ($question->type=='File Upload'))  {
			$fID=intval($answerSet->answers[$question->ffID]['value']);
			if($fID != '') {
				$file=File::getByID($fID);
				$fv=$file->getApprovedVersion();
				switch($property) {
					case 'title':
						$answerValue = $fv->getTitle();
						break;
					case 'description':
						$answerValue = $fv->getDescription();
						break;
					case 'tags':
						$answerValue = $fv->getTags();
						break;
					case 'type':
						$answerValue = $fv->getType();
						break;
					case 'filename':
						$answerValue = $fv->getFileName();
						break;
					case 'size':
						$answerValue = $fv->getSize();
						break;
					case 'fullsize':
						$answerValue = number_format($fv->getFullSize());
						break;
					case 'id':
						$answerValue = $ID;
						break;
					default:
						$answerValue = $fv->getRelativePath();
				}
			} else {
				$answerValue = '';
			}
		} else {
			$answerValue = str_replace('&','&amp;',$answerSet->answers[$question->ffID]['value']);
		}
		return trim($answerValue);
	}
	
	public function getAnswerValueByFieldName($fields,$questionName,$answerSet,$property='',$imgWidth='',$imgHeight='') {
		if(count($fields) > 0) {
			foreach($fields as $questionID=>$question){
				if($question->label == $questionName) {
					if(($imgWidth != '') || ($imgHeight != '')) {
						$uh = Loader::helper('concrete/urls');
						$imgSizePath = $uh->getToolsURL('imgsize','sixeightdatadisplay');
						
						if($imgWidth != '') {
							$widthParam = '&amp;w=' . intval($imgWidth);
						}
						
						if($imgHeight != '') {
							$heightParam = '&amp;h=' . intval($imgHeight);
						}
						return $imgSizePath . '?img=' . $this->getAnswerValueFromAnswerSet($answerSet,$question,$property) . $widthParam . $heightParam;
					} else {
						return $this->getAnswerValueFromAnswerSet($answerSet,$question,$property);
					}
				}	
			}
		}
		return false;
	}
	
	function xml2xhtml($xml) {
	    return preg_replace_callback('#<(\w+)([^>]*)\s*/>#s', create_function('$m', '
	        $xhtml_tags = array("br", "hr", "input", "frame", "img", "area", "link", "col", "base", "basefont", "param");
	        return in_array($m[1], $xhtml_tags) ? "<$m[1]$m[2] />" : "<$m[1]$m[2]></$m[1]>";
	    '), $xml);
	}

}

?>