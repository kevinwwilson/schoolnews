
<?php
require ("../concrete/libraries/3rdparty/adodb/adodb.inc.php");
require ("../config/site.php");

header('Content-Type: application/javascript');

    $days = 15;
	$db = NewADOConnection('mysql');
	$db->Connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	
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
                        'values'=> array(
                            $rs->fields['handle']=>$rs->fields['value']
                            )
                        );
                        
                    }

                    $rs->MoveNext();
                }
                usort($answerList, 'compare_date');
                
                
	echo $_GET['callback']."(".json_encode($answerList).");"; 
	//echo json_encode($value);

        
        function compare_date($a, $b) {
                if ($a['values']['date'] == $b['values']['date']) {
                    return 0;
                }
                return ($a['values']['date'] > $b['values']['date']) ? -1 : 1;
        }
        ?>