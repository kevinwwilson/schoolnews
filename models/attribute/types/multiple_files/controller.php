<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

class MultipleFilesAttributeTypeController extends AttributeTypeController  { 

	//protected $searchIndexFieldDefinition = 'X NULL';
	
	public function getValue() {
		$db = Loader::db();
		$value = $db->GetOne("select value from atMultipleFiles where avID = ?", array($this->getAttributeValueID()));
		return $value;	 
	}
	
	
	public function getDisplayValue() {
		return $this->getValue();
	}


	public function searchForm($list) {
		$db = Loader::db();
		$list->filterByAttribute($this->attributeKey->getAttributeKeyHandle(), '%' . $this->request('value') . '%', 'like');
		return $list;
	}	
	
	public function search() { 
		$f = Loader::helper('form');
		print $f->text($this->field('value'), $value);
	}	 
	
	public function form(){   
		$al = Loader::helper('concrete/asset_library');    
	}	

	// run when we call setAttribute(), instead of saving through the UI
	public function saveValue( $fIDs=array() , $fName = array()) {
            if ($fIDs == null) return;
            $db = Loader::db();
            
            if(!is_array($fIDs) && $fIDs != 0){			
                    $cfid = explode('||',$fIDs);
                    $fIDs = explode('^',$cfid[0]);
                    $captionList = explode('^',$cfid[1]);
            } else {
                $captionList = $this->prepareCaptions($fIDs, $fName);
            }
            $cleanFIDs=array();
            $i = 0;
            foreach($fIDs as $fID)
            {
                    $cleanFIDs[]=intval($fID).'||'. htmlspecialchars($captionList[$i]);
                    $i++;
            }
            $cleanFIDs = array_unique($cleanFIDs);
            $db->Replace('atMultipleFiles', array('avID' => $this->getAttributeValueID(), 'value' => join('^',$cleanFIDs)), 'avID', true);
	}
        
        /*
         * Process the caption list that was saved from the form
         */
        public function prepareCaptions($fIDs, $fName) {
            //check to see that the nuumber of items in each array is the same.  If it is not, then one of the 
            //files was unchecked, and the corresponding caption will need to be removed.
            if (count($fIDs) < count($fName)) {
                $fName = $this->cleanCaptions($fIDs, $fName);
            }

            //take out the nested array part now that we've matched the caption array to the file list array
            foreach ($fName as $captionArray) {
                foreach ($captionArray as $caption) {
                    $captionList[] = $caption;
                }
            }
            return $captionList;
        }


        /*
         * Returns a caption array of the same number and order as the file list 
         */
        public function cleanCaptions($fileList, $captionList) {
            $i=0;
            $cleanCaptions = array();
            foreach($fileList as $fileId) {
                if (is_string($captionList[$i][$fileId])){
                    $cleanCaptions[$i][$fileId] = $captionList[$i][$fileId];
                } else {
                    $cleanCaptions[$i][$fileId]= $this->findCaption($fileId, $captionList);
                } 
                $i++;
            }
            
            return $cleanCaptions;
        }
        
        /*
         * Finds the Caption with Id of $id in the array $captionList
         * The captionlist is an array within an array to allow for duplicat file Id's in a single slideshow
         * and while still properly saving the slideshow and captions.
         */
        public function findCaption($id, $captionList){
            foreach ($captionList as $caption) {
                if (strlen($caption[$id]) > 0 ) {
                    return $caption[$id];
                }
            }
            
        }
    
	
	public function deleteKey() {
		$db = Loader::db();
		$arr = $this->attributeKey->getAttributeValueIDList();
		foreach($arr as $id) {
			$db->Execute('delete from atMultipleFiles where avID = ?', array($id));
		}
	}
	
	public function saveForm($data) { 
	   
	    
		$db = Loader::db();
		$this->saveValue($data['fID'], $data['fName']);
	}
	
	public function deleteValue() {
		$db = Loader::db();
		$db->Execute('delete from atMultipleFiles where avID = ?', array($this->getAttributeValueID()));
	}
	
	
	static public function getFiles($valueStr=''){  
		$files=array();
		foreach(explode('^',$valueStr) as $fIDName){
			
			$split = explode('||', $fIDName);
			$fID = $split[0];
			if(!intval($fID)) continue;
			$file = File::getByID(intval($fID));
			if(!is_object($file) || !$file->getFileID()) continue;  
			
			$values = array($file,$split[1]);
			$files[]=$values; 
			
		}  	
		return $files; 
	}
	
}


/*
class MultipleFilesAttributeTypeValue extends Object { 

	public static function getByID($avID) {
		$db = Loader::db();
		$value = $db->GetRow("select * from atMultipleFiles where avID = ?", array($avID));
		$mfatv = new MultipleFilesAttributeTypeValue();
		$mfatv->setPropertiesFromArray($value);
		if ($value['avID']) {
			return $mfatv;
		}
	} 
	
	public function __construct() {
		
	}	 
	
	public function getFiles(){  
		$files=array();
		foreach(explode(',',$this->value) as $fID){
			if(!intval($fID)) continue;
			$file = File::getByID(intval($fID));
			if(!is_object($file) || !$file->getFileID()) continue;   
			$files[]=$file; 
		}  	
		return $files; 
	}
	
	public function __toString() {
		$fileNames=array();
		foreach($this->getFiles() as $f){
			$fv = $f->getApprovedVersion();
			$fileNames[]=$fv->getTitle();
		} 
		return join(', ',$fileNames);	
	}
}
*/ 