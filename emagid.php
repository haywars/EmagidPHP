<?php 
namespace Emagid;




/**
* Base class for eMagid libraries 
*/
class Emagid{
	/** 
	* @var Object 
	* 	page object, will contain SEO , data and other page specific handlers
	*/ 
	public $page = null  ;

	/** 
	* @var Determine whether to include debug module or not .
	*/ 
	public $debug = false ;

	/** 
	* @var String base path
	*/ 
	public $base_path = '/' ;

	/**
	* @var Array list of folders to include 
	*/
	public $include_paths = [];

	/**
	* @var an object that contains the connection string parameters (db_name, username, password, host)
	*/
	public $connection_string;


	/**
	* @var Object \Emagid\Mvc\Conroller.
	*/
	public $controller ;

	/**
	* @var string acive template's name
	*/
	public $template = null ;


	/**
	* @var int default error_reporting value 
	*/
	private static $default_error_reporting = 0 ;


	/**
	* Defualt constructor 
	*
	* $params Array basic settings, such as directories, DB connection etc... 
	*/
	public function __construct($params = []){

		$this->connection_string = new \stdClass;
		

		if(count($params)>0)
			$this->readParams($params);

		$this->loadLibraries($this->base_path.'libs/Emagid', false);

		if($this->debug){ // load kint for debugging 
			require_once($this->base_path.'libs/Emagid/_includes/kint/Kint.class.php');
		}
		
		$this->page = new \Emagid\Page\Page(); 


		if($this->debug){
			//set_error_handler('\Emagid\Emagid::emagidErrorHandler');
		}

	}



	/**
	* Breaks the parameters from the constructor into local variables.
	*/
	private function readParams($params){
		foreach ($params as $key => $value) {
			if(is_array($value)){
				$this->{$key} = $this->array_to_object($value);
			}else{
				$this->{$key} = $value;
			}
			
		}


	}

	/** 
	* convert an array to an object 
	*
	* @param Array
	* @return strClass 
	*/
	private function array_to_object($array) {
	  $obj = new \stdClass;

	  foreach($array as $k => $v) {
	     if(is_array($v)) {
	        $obj->{$k} = $this->array_to_object($v); //RECURSION
	     } else {
	        $obj->{$k} = $v;
	     }
	  }

	  return $obj;
	} 


	/**
	* Include libraries outside the emagid library 
	* 
	* @param $arr Array - array of include files, will override the default class config
	*/
	public function loadIncludes($arr = null ){
		if(isset($arr) && $arr != null){
			$this->include_paths = $arr;
		}

		foreach($this->include_paths as $folder ){


			$this->loadLibraries($folder);
			
		}

	}


	/**
	* Load the eMagid libraries 
	*/
	function loadLibraries($folder , $loadFiles = true){

		if ($handle = opendir($folder)) {
		    /* Loop through directories  */
		    while (false !== ($entry = readdir($handle))) {

		    	if(!startsWith($entry,'.') && !startsWith($entry,'_')){ // skip git folders, up folder,etc... 
		    	
			    	if(stristr($entry,".php") ){
			    		if($loadFiles){ // load all files in the current directory
			    			require_once($folder."/".$entry);
			    		}
			    	} else { // it's a folder
			    			$this->loadLibraries($folder."/".$entry); // recursion 
			    	}
		        
				}
		    }

		    closedir($handle);
		}
	}

	

	/**
	* Initializer the library's internal debugger and prevent PHP errors from showing 
	*/
	static function startDebugger(){
		self::$default_error_reporting = ini_get('error_reporting');

		error_reporting(0);
	}




	static function emagidErrorHandler($errno, $errstr, $errfile, $errline){
		
		
\Kint::trace();
		
	}

	

}



	/** 
	* Checks whether a strings starts with a specific string.
	*
	* @todo Move this function to functions.inc.php
	*/
	function startsWith($haystack,$needle,$case=true){
		if($case)
       		return strpos($haystack, $needle, 0) === 0;

   		return stripos($haystack, $needle, 0) === 0;
	}



?>