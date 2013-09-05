<?php
class Logger{
	public static function log($msg){
		
	}
}

class MailMerge {
	private $mm_data_dir;
	private $obj;
	private $datasource_file = 'ds.doc';
	private $header_file = 'header.doc';
	private $fieldcnt;
	private $rowcnt;
	private $template;
	private $visible = false;
	private $list;

	public function __construct($list = NULL, $data_dir = 'data') {
		// this is the path to your data dir.
		$this->mm_data_dir = $data_dir;
		$this->setList($list);
	}

	public function Execute() {
		if($this->initilize()){
			if( count( $this->list ) > 0 ) {
				if(isset($this->template)) {
					// $this->CreateHeaderFile();
					// $this->CreateDataSource();
					return $this->CreateDocument($this->template);
				}
			} else return FALSE;
		} else{
			return FALSE;
		}
	}
	
	public function setList($list = NULL) {
		if(is_array($list)) $this->list = $list;
	}
	
	public function Template($template = NULL) {
		$this->template = $template;
	}

	/*
	public function __destruct() {
		//remove the temp files
		$this->Quit();
	}
	*/

	private function initilize() {
		$this->rowcnt = count($this->list);
		$this->fieldcnt = count($this->list[0]);
		// $this->obj = new COM("word.application") or die("Unable to instanciate Word");
		$this->obj = new COM("word.application", NULL, CP_UTF8) or die("Unable to instanciate Word");
		
		$this->obj->Visible = $this->visible;
		Logger::log('Word -> Application Opened.');
		return true;
	}

	public function Quit() {
		try{
			$this->obj->Quit();
			Logger::log('Word -> Application Quit.');
		} catch (Exception $e){
			
		}
	}

	private function CreateDocument($template) {
		try {
			$this->obj->Documents->Open($this->mm_data_dir."/".$template);
		} catch(Exception $e){
			try{
				$this->obj->Documents("".$template)->Close();
			} catch (Exception $ee){}
			
			echo $e;
			return false;
		}
		Logger::log('Word -> '.$this->obj->ActiveDocument->Name().' Document Opened.');

		for($i = 0; $i < $this->rowcnt; $i++) {
			foreach($this->list[$i] as $key => $value) {
				$this->obj->Selection->Find->Execute("{".$key."}",false,true,false,false,false,true,1,false,$value,2);
			}
			
			try{
				$this->obj->ActiveWindow->ActivePane->View->SeekView = 2; // first header
				foreach($this->list[$i] as $key => $value) {
					$this->obj->Selection->Find->Execute("{".$key."}",false,true,false,false,false,true,1,false,$value,2);
				}
				try {
					$this->obj->ActiveWindow->ActivePane->View->NextHeaderFooter(); // next header
					foreach($this->list[$i] as $key => $value) {
						$this->obj->Selection->Find->Execute("{".$key."}",false,true,false,false,false,true,1,false,$value,2);
					}
					$this->obj->ActiveWindow->ActivePane->View->NextHeaderFooter();
					foreach($this->list[$i] as $key => $value) {
						$this->obj->Selection->Find->Execute("{".$key."}",false,true,false,false,false,true,1,false,$value,2);
					}
				} catch(Exception $e){
					// echo $e;
				}
			} catch (Exception $e1){
				$this->obj->ActiveWindow->ActivePane->View->SeekView = 1; // current header
				foreach($this->list[$i] as $key => $value) {
					$this->obj->Selection->Find->Execute("{".$key."}",false,true,false,false,false,true,1,false,$value,2);
				}
			}
			
			
			try{
				$this->obj->ActiveWindow->ActivePane->View->SeekView = 5; // first footer
				foreach($this->list[$i] as $key => $value) {
					$this->obj->Selection->Find->Execute("{".$key."}",false,true,false,false,false,true,1,false,$value,2);
				}
				try {
					$this->obj->ActiveWindow->ActivePane->View->NextHeaderFooter(); // next footer
					foreach($this->list[$i] as $key => $value) {
						$this->obj->Selection->Find->Execute("{".$key."}",false,true,false,false,false,true,1,false,$value,2);
					}
					$this->obj->ActiveWindow->ActivePane->View->NextHeaderFooter();// next footer
					foreach($this->list[$i] as $key => $value) {
						$this->obj->Selection->Find->Execute("{".$key."}",false,true,false,false,false,true,1,false,$value,2);
					}
				} catch(Exception $e){
					// echo $e;
				}
			} catch (Exception $e1){
				$this->obj->ActiveWindow->ActivePane->View->SeekView = 4; // current footer
				foreach($this->list[$i] as $key => $value) {
					$this->obj->Selection->Find->Execute("{".$key."}",false,true,false,false,false,true,1,false,$value,2);
				}
			}
		}
		
		try{
			$this->obj->ActiveDocument->Save();
			$this->obj->Documents("".$template)->Close();
		} catch (Exception $eee) {}
		return true;
	}
}
?>