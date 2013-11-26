<?
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
		$db = Loader::db();
		if(!is_array($fIDs) && $fIDs != 0){			
			$cfid = explode('||',$fIDs);
			$fIDs = explode(',',$cfid[0]);
			$fName = explode(',',$cfid[1]);
		}
		$cleanFIDs=array();
		$i = 0;
		foreach($fIDs as $fID)
		{
			$cleanFIDs[]=intval($fID).'||'.$fName[$i];
			$i++;
		}
		$cleanFIDs = array_unique($cleanFIDs);
		$db->Replace('atMultipleFiles', array('avID' => $this->getAttributeValueID(), 'value' => join(',',$cleanFIDs)), 'avID', true);
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
		foreach(explode(',',$valueStr) as $fIDName){
			
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