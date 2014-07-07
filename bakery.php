<?php 


function redString($str){
	return "\033[0;31m".$str."\033[0m";
}

class Concrete5Shell{

	private $error;
	private $action;

	private $last_page;
	private $last_pages;

	private $storage;
	
	private $options;

	public function __construct(&$options = array()){
		$this->options = $options;
		$this->storage = array();
	}

	public function __call($name,$arguments){
		
		$var = substr($name,3);
		if(strncmp($name,"set",3) == 0){
			$this->storage[$var] = $arguments[0];
		}

		if(strncmp($name,"get",3) == 0){
			$data = $this->storage[$var];
			if(isset($data))
				return $data;
			else
				return "";
		}
	
	}
	
	public function getError(){ echo $this->error; }

	public function setLastPage($page){
		$this->last_page = $page;
	}
	public function setLastPages($page){
		$this->last_pages = $page;
	}
	public function getLastPage(){
		return $this->last_page;
	}

	public function getLastPages(){
		return $this->last_pages;
	}

	public function parseCommand($cmd){

		$args = explode(" ",rtrim($cmd));
		
		$action = $args[0];
		$options = $this->options;
		$i = 0;
		$found_match = false;

		while(isset($options[$action])){
			$func = $options[$action];
			if(is_callable($func)){
				//Make the call

				array_shift($args);
				array_shift($args);
				$data = json_decode(implode(" ",$args),true);
				if($data == NULL) {
					$this->error = "Concrete5::Invalid JSON string\n";
				}
				
				$func($data,$this);

				//For error reporting 
				$found_match = true;
				break;
			}

			$i++;
			$action = $args[$i];
			$options = $func; 
		}

		if(!$found_match){
			$this->error = "Concrete5::Cannot find command";
			return -1;
		}
		
	}

	


}


include 'confs/bakery_setup.php';

include 'confs/bakery_base_options.php';
include 'bakery_user_options.php';

print "Concrete 5 Developer Shell.\n\n";

$options = array_merge($options,$user_options);
//Init with base options
$shell = new Concrete5Shell($options);

$fp = fopen('php://stdin', 'r');
$last_line = false;
$message = '';
while (!$last_line) {
	print "\n > ";
    $next_line = fgets($fp, 1024); // read the special file to get the user input from keyboard
    if ($next_line == "exit".PHP_EOL) {
      $last_line = true;
    } else {
      //$message .= $next_line;
       $result = $shell->parseCommand($next_line);
       if($result == -1 )
       		$shell->getError();
    }
}

fclose($fp);


?>