<?php
defined('C5_EXECUTE') or die("Access Denied.");
class GetNewsInfoHelper {
	public function getInfo($form, $field, $record) {
		$db = Loader::db();
		$q = "SELECT value
			FROM sixeightformsAnswers
			WHERE ffID IN (SELECT ffID FROM sixeightformsFields
			WHERE fID IN (SELECT fID FROM sixeightforms WHERE handle=?) 
			AND
			handle= ?) 
			AND asID in (SELECT MAX(asID) FROM sixeightformsAnswerSets WHERE recordID=?)";
		$value = $db->GetOne($q, array($form, $field, $record));
		return $value;
	}
	
	
        public function getRecentNews($days) {
            $db = Loader::db();
            $answerList = array();
            
            $q = "Select ss.recordID, ans.asID, ss.dateUpdated, fields.handle, ans.value
                    FROM sixeightformsanswers ans
                    RIGHT OUTER JOIN (
                    SELECT * FROM sixeightformsAnswerSets
                        WHERE asID IN (SELECT MAX(asID) FROM sixeightformsAnswerSets GROUP BY recordID) 
                        AND isApproved =1
                        AND isDeleted=0
                        AND fID in (Select fID FROM sixeightforms WHERE handle = ?)
                    ) ss
                    on  ( ans.asID = ss.asID)
                    Left OUTER Join sixeightformsfields fields on ans.ffID = fields.ffID
                    WHERE DATEDIFF(CURDATE(), FROM_UNIXTIME(ss.dateUpdated)) <=?";
            
             $rs = $db->Execute($q, array(FORM_HANDLE, $days));
            $i=0;
            $currentRecord = 0;
            while (!$rs->EOF) {
                   
                   if ($currentRecord != $rs->fields['recordID']) {
                        $currentRecord = $rs->fields['recordID'];
                        $i++;
                    }
                
                 if (isset($answerList[$i]['recordID'])) {
                     $answerList[$i]['values'] = $answerList[$i]['values'] + array( $rs->fields['handle']=>$rs->fields['value']);
                     }
                 else {
                    $answerList[$i] = array (
                        'recordID' => $rs->fields['recordID'],
                        'asID'=> $rs->fields['asID'],
                        'dateUpdated'=> $rs->fields['dateUpdated'],
                        'link'=> NEWS_DETAIL_URL . '?rID=' . $rs->fields['recordID'],
                        'values'=> array(
                            $rs->fields['handle']=>$rs->fields['value']
                            )
                        );
                        
                    }

                    $rs->MoveNext();
                }
                usort($answerList, array('self', 'compare_date'));
                
                return $answerList;
          
        }
        
        static function compare_date($a, $b) {
                if ($a['values']['date'] == $b['values']['date']) {
                    return 0;
                }
                return ($a['values']['date'] > $b['values']['date']) ? -1 : 1;
        
        }
        
        public function createNewsTable() {
            $db = Loader::db();
            $answerList = array();
            $fieldList = array();
            
            $q = "Select ss.recordID, ans.asID, ss.dateUpdated, fields.handle, ans.value
                    FROM sixeightformsanswers ans
                    RIGHT OUTER JOIN (
                    SELECT * FROM sixeightformsAnswerSets
                        WHERE asID IN (SELECT MAX(asID) FROM sixeightformsAnswerSets GROUP BY recordID) 
                        AND isApproved =0
                        AND isDeleted=0
                        AND fID in (Select fID FROM sixeightforms WHERE handle = ?)
                    ) ss
                    on  ( ans.asID = ss.asID)
                    Left OUTER Join sixeightformsfields fields on ans.ffID = fields.ffID;";
            
             $rs = $db->Execute($q, array(FORM_HANDLE));
            $i=0;
            $currentRecord = 0;
            while (!$rs->EOF) {
                   //have we already started processing this record?
                   if ($currentRecord != $rs->fields['recordID']) {
                        $currentRecord = $rs->fields['recordID'];
                        $i++;
                    }
                
                  //we've seen this recordID before, add the new value to the values array  
                 if (isset($answerList[$i]['recordID'])) {
                     $answerList[$i]['values'] = $answerList[$i]['values'] + array( $rs->fields['handle']=>$rs->fields['value']);
                     }
                 else {
                     //never done this record before, create new element with meta info about record and add first value
                    $answerList[$i] = array (
                        'recordID' => $rs->fields['recordID'],
                        'asID'=> $rs->fields['asID'],
                        'dateUpdated'=> $rs->fields['dateUpdated'],
                        'link'=> NEWS_DETAIL_URL . '?rID=' . $rs->fields['recordID'],
                        'values'=> array(
                            $rs->fields['handle']=>$rs->fields['value']
                            )
                        );
                        
                    }
                    
                    //keep a running list of all fields encountered
                    //Underscores are nicer than hyphens for SQL generated later #hack
                    $handle = str_replace ('-','_',$rs->fields['handle']);
                    if (!in_array ($handle,$fieldList)) {
                        $fieldList[]=$handle;
                    }
                    
                    $rs->MoveNext();
                }
                
                //put the most recent news item on top
                usort($answerList, array('self', 'compare_date'));
            
               
            $db->Execute('DROP TABLE IF EXISTS snn_news_temp');             

            $q = "CREATE TABLE snn_news_temp (
                id int(11) NOT NULL AUTO_INCREMENT, 
                rID int(11) NOT NULL,
                dateUpdated int(15) NOT NULL,
                link text NOT NULL,";
            
            foreach ($fieldList as $field) {
            $q .= $field . " text,";
            }
            $q  .= "PRIMARY KEY (id)  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
            
             $db->Execute($q);
                
            foreach ($answerList as  $item ) {
                $numValues = count($item[values]);
                
                $columnList = " (";
                $columnList .="rID,dateUpdated,link,";
                
                //yeah we replaced hyphens with underscores before #hack
                $columnList .= implode(', ', str_replace ('-','_',array_keys($item[values])));
                $columnList .= ")";
                        
                //generate bunches of question marks
                //add +3 for the record id, last updated date and link which isn't one of the set values
                $parameterList = " (?";
                for ($i=1; $i<$numValues+3; ++$i) {
                    $parameterList .=",?";
                }
                $parameterList .= ") ";
                $values = array(
                    recordID=>$item[recordID],
                    dateUpdate=>$item[dateUpdated],
                    link=>$item[link]
                    ) + $item[values];
                //generate the sql
                $q = 'INSERT INTO snn_news_temp' . $columnList;
                $q .= 'VALUES' . $parameterList .';';
                
                
                $db->Execute($q, $values);
                
               
            }
            
             // Use transactions.  
                $db->StartTrans();
                
                $db->Execute('DROP TABLE IF EXISTS snn_news');
                $db->Execute ('ALTER TABLE snn_news_temp RENAME TO snn_news');
                
                $db->CompleteTrans();
            
        }
        
        
       public function getNews() {
       // general purpose function to retrieve news from the consolidated snn_news table
       
           
       } 
	
}

?>