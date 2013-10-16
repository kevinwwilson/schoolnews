<?php  

	Loader::model('answer_set','sixeightforms');

	class sixeightAnswerSetList extends Object {
	
		function get($fID) {
			$asl = new sixeightAnswerSetList;
			$asl->fID = $fID;
			$asl->pageNum = 1;
			$asl->pageSize = 10;
			$asl->startDate = '';
			$asl->endDate = '';
			$asl->filters = array();
			$asl->searchQuery = '';
			$asl->sortOrder = 'ASC';
			$asl->sortField = 0;
			$asl->requireOwnership = false;
			$asl->requireApproval = false;
			$asl->includeExpired = false;
			$asl->isPrePopulated = false;
			$asl->excludeExported = false;
			$asl->answerSets = array();
			return $asl;
		}
		
		function includeExpired($value=true) {
			if($value) {
				$this->includeExpired = true;
			} else {
				$this->includeExpired = false;
			}
		}
		
		function excludeExported($value=true) {
			if($value) {
				$this->excludeExported = true;
			} else {
				$this->excludeExported = false;
			}
		}
		
		function requireApproval($value=true) {
			if($value) {
				$this->requireApproval = true;
			} else {
				$this->requireApproval = false;
			}
		}
		
		function requireOwnership($value=true) {
			if($value) {
				$this->requireOwnership = true;
			} else {
				$this->requireOwnership = false;
			}
		}
	
		function setSearchQuery($q) {
			$this->searchQuery = $q;
		}
		
		function getSearchQuery() {
			if($this->searchQuery == '') {
				return false;
			} else {
				$words = explode(' ',$this->searchQuery);
				return $words;
			}
		}
		
		function addFilter($ffID,$value) {
			$this->filters[] = $this->getOrCreateFilter($ffID,$value);
		}
		
		function getOrCreateFilter($ffID,$value) {
			$db = Loader::db();
			$filter = $db->getRow("SELECT filterID, ffID, value FROM sixeightformsFilters WHERE ffID = ? AND value = ?",array($ffID,$value));
			if($filter) {
				return $filter;
			} else {
				$db->execute("INSERT INTO sixeightformsFilters (filterID, ffID, value) VALUES (0,?,?)",array($ffID,$value));
				return array('filterID' => $db->Insert_ID(),'ffID' => $ffID,'value' => $value);
			}
		}
		
		function getFilters() {
			return $this->filters;
		}
		
		function setSortOrder($order = '') {
			if($order == 'DESC') {
				$this->sortOrder = 'DESC';
			} elseif ($order == 'RAND') {
				$this->sortOrder = 'RAND';
			} else {
				$this->sortOrder = 'ASC';
			}
		}
		
		function getSortOrder() {
			return $this->sortOrder;
		}
		
		function setSortField($ffID) {
			$this->sortField = intval($ffID);
		}
		
		function getSortField() {
			return intval($this->sortField);
		}
		
		function setPageNum($p) {
			$this->pageNum = intval($p);
		}
		
		function getPageNum() {
			return $this->pageNum;
		}
		
		function setPageSize($s) {
			$this->pageSize = intval($s);
		}
		
		function getPageSize() {
			return $this->pageSize;
		}

		function setDateRange($startDate,$endDate) {
			//Date format should be Unix timestamp
			$this->startDate = $startDate;
			$this->endDate = $endDate + 86400; //Add 86,400 to make it the END of that day
		}
		
		function setAnswerSetIDs() {
			//Task:		Determine which answer sets to get without getting the full answer set data
			//Reason: 	Outside of the filtering, we could just setup a long SQL query, but the
			//			filtering requires some extra SQL queries to check the actual record data.
			
			$db = Loader::db();
			$SQLvars = array();
			$SQLvars[] = $this->fID;
			
			// Run some checks before setting up the query		
			
			// 1. Setup sort order SQL
			if($this->getSortField() != 0) {
				$SQLvars[] = intval($this->getSortField());
				$sortField = sixeightField::getByID($this->getSortField());
				if($sortField->type == 'Number') {
					$sortType = '(a.value + 0)';
				} else {
					$sortType = 'a.value';
				}
			} else {
				$sortType = 'a.value';
			}
			
			$sortOrderField = 'dateSubmitted';
			
			if($this->getSortOrder() == 'RAND') {
				$sortOrderSQL = 'RAND()';
				$sortOrderField = '';
			} else {
				$sortOrderSQL = $this->getSortOrder();
			}
			
			// 2. Include expired records?
			if($this->includeExpired) {
				$expiredSQL = '';
			} else {
				$expiredSQL = "AND (expiration > '" . time() . "' OR expiration IS NULL)";
			}
			
			// 3. Get approved records only?			
			if($this->requireApproval) {
				$approvalSQL = 'AND isApproved = 1';
			} else {
				$approvalSQL = '';
			}
			
			// 4. Only show records owned by the current user?
			if($this->requireOwnership) {
				$owner = new User();
				$ownershipSQL = "AND creator = " . intval($owner->getUserID());
			} else {
				$ownershipSQL = '';
			}
			
			// 5. Only show records that have not yet been exported?
			if($this->excludeExported) {
				$f = sixeightForm::getByID($this->fID);
				$exportedSQL = "AND dateSubmitted > " . intval($f->properties['exportTimestamp']);
			} else {
				$exportedSQL = '';
			}
			
			// 6. Search according to the date range specified
			if($this->startDate != '') {
				$startDateSQL = "AND dateSubmitted >= " . intval($this->startDate);
			} else {
				$startDateSQL = '';
			}
			
			if($this->endDate != '') {
				$endDateSQL = "AND dateSubmitted <= " . intval($this->endDate);
			} else {
				$endDateSQL = '';
			}
			
			// 7. Is there are search query?
			if($this->searchQuery != '') {
				$queryWords = explode(' ',$this->searchQuery);
				if(count($queryWords) == 1) {
					$searchSQL = "AND searchIndex LIKE ?";
					$SQLvars[] = '%' . $this->searchQuery . '%';
				} else {
					$searchSQL = "AND (";
					$i = 0;
					foreach($queryWords as $word) {
						if($i > 0) {
							$searchSQL .= ' AND ';
						}
						$searchSQL .= "searchIndex LIKE ?";
						$SQLvars[] = '%' . $word . '%';
						$i++;
					}
					$searchSQL .= ')';
				}
			}
			
			// 8. Setup matching filters
			// 		1. Get the filters
			//		2. Get answers that match the filter
			//		3. Add the filter ID to "matchingFilters" filed for the answer sets that match the filter
			//		4. Set $filterSQL = '(matchingFilters = X) OR (matchingFilters = X)…'
			$filters = $this->getFilters();
			if(count($filters) > 0) {
				foreach($filters as $filter) {
					$matchingAnswers = $db->getAll("SELECT asID FROM sixeightformsAnswers WHERE ffID = ? AND value LIKE ?",array($filter['ffID'],'%' . $filter['value'] . '%'));
					if(is_array($matchingAnswers)) {
						foreach($matchingAnswers as $ma) {
							$asRow = $db->getRow("SELECT count(asID) as total FROM sixeightformsAnswerSets WHERE asID=? AND matchingFilters LIKE ?",array($ma['asID'],'%,' . $filter['filterID'] . ',%'));
							if(intval($asRow['total']) == 0) {
								$db->execute('UPDATE sixeightformsAnswerSets SET matchingFilters=CONCAT_WS(",",matchingFilters,?) WHERE asID=?',array($filter['filterID'] . ',',$ma['asID']));
							}
						}
					}
					$filterSQL .= 'AND matchingFilters LIKE ?';
					$SQLvars[] = '%,' . $filter['filterID'] . ',%';
				}
			}
			
			// 9. Get the answer set ID's
			$pageSize = $this->getPageSize();
			$startAt = ($this->getPageNum() - 1) * $pageSize;
			if($startAt < 0) {
				$startAt = 0;
			}
			
			if($this->getPageSize() == 0) {
				$limitSQL = '';
			} else {
				$limitSQL = "LIMIT $startAt, $pageSize";
			}
			
			if(($this->getSortField() == 0) || ($sortOrderField == '')) {
				$query = "SELECT asID FROM sixeightformsAnswerSets WHERE fID = ? AND isDeleted != 1 $expiredSQL $approvalSQL $ownershipSQL $exportedSQL $startDateSQL $endDateSQL $searchSQL $filterSQL ORDER BY $sortOrderField $sortOrderSQL $limitSQL";
			} else {
				$query = "SELECT ans.asID, a.value FROM sixeightformsAnswerSets ans, sixeightformsAnswers a WHERE ans.fID = ? AND a.ffID = ? AND a.asID = ans.asID AND ans.isDeleted != 1 $expiredSQL $approvalSQL $ownershipSQL $exportedSQL $startDateSQL $endDateSQL $searchSQL $filterSQL ORDER BY $sortType $sortOrderSQL $limitSQL";
			}
			
			$answerSetRecords = $db->getAll($query,$SQLvars);
			if(count($answerSetRecords) > 0) {
				$asIDs = array();
				foreach($answerSetRecords as $asr) {
					$asIDs[] = $asr['asID'];
				}
			}
			
			$this->isPrePopulated = true;
			if(count($asIDs) > 0) {
				foreach($asIDs as $asID) {
					$this->answerSets[] = $asID;
				}
			}
		}
		
		function getAnswerSetIDs() {
			if($this->isPrePopulated) {
				return $this->answerSets;
			} else {
				$this->setAnswerSetIDs();
				return $this->answerSets;
			}
		}
		
	
		function getAnswerSets() {
			if(!$this->isPrePopulated) {
				//If the answer set list has not been pre populated, do so now
				$this->getAnswerSetIDs();
			}
			
			//Get the answer set data according to the answer sets returned in prePopulate
			$answerSets = array();
			foreach($this->answerSets as $asID) {
				$answerSets[] = sixeightAnswerSet::getByID($asID);
			}
			return $answerSets;
		}
		
		function getAnswerSetCount() {
			if(!$this->isPrePopulated) {
				//If the answer set list has not been pre populated, do so now
				$this->getAnswerSetIDs();
			}
			return count($this->getAnswerSetIDs());
		}
		
		function getPageCount() {
			if(!$this->isPrePopulated) {
				//If the answer set list has not been pre populated, do so now
				$this->getAnswerSetIDs();
			}
		}
	
	}