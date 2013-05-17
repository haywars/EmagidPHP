<?php 
namespace Emagid\Page;

/**
* base page class, handles template and pages 
*/ 
class Page{
	
	/**
	* @var array[] list of css documents to load
	*/
	public $css_documents;

	/**
	* @var Object
	* 		SEO object - metatags, title ,etc... 
	*/
	public $seo;


	public function __construct(){
		$this->seo = (object)['title'=>'', 'description'=>''];
	}

	public function loadCss(){
		require_once("cssmin-v3.0.1.php");

		

		if(isset($this->css_documents) && is_array($this->css_documents)){
			foreach($this->css_documents as $css){
				$css = getcwd().str_replace('/','\\',$css);

				//echo($css);
				//$result = \CssMin::minify(file_get_contents($css));

				printf("<link href=\"%s\" rel=\"stylesheet\" type=\"text/css\" />",$css);
			}
		}
	}


	/**
	*	Load the view from $emagid->mvc . 
	*/
	public function renderBody(){
		global $emagid; 

		dd($emagid->mvc); 

	}

	/**
	 * This function is to replace PHP's extremely buggy realpath().
	 * @param string The original path, can be relative etc.
	 * @return string The resolved path, it might not exist.
	 */
	function truepath($path){
	    // whether $path is unix or not
	    $unipath=strlen($path)==0 || $path{0}!='/';
	    // attempts to detect if path is relative in which case, add cwd
	    if(strpos($path,':')===false && $unipath)
	        $path=getcwd().DIRECTORY_SEPARATOR.$path;


	    // resolve path parts (single dot, double dot and double delimiters)
	    $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
	    $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
	    $absolutes = array();
	    foreach ($parts as $part) {
	        if ('.'  == $part) continue;
	        if ('..' == $part) {
	            array_pop($absolutes);
	        } else {
	            $absolutes[] = $part;
	        }
	    }


	    $path=implode(DIRECTORY_SEPARATOR, $absolutes);


	    // resolve any symlinks
	    if(file_exists($path) && linkinfo($path)>0)$path=readlink($path);
	    // put initial separator that could have been lost
	    $path=!$unipath ? '/'.$path : $path;
	    return $path;
	}
}

?>