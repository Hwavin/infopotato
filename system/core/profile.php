<?php
/**
 * Class to time the execution of method calls.
 * Example usage:
 *   $p = new Profile();
 *   $time = $p->profile("classname", "methodname", array(arg1, arg2, ...));
 *   $p->print_details();
 *   
 * You can also provide an optional number to profile, which will result 
 * in the method getting called that many times. Details are then recoded 
 * about the total execution time, average time, and worst single time.
 *
 * @author Ben Dowling - www.coderholic.com
 */
class Profile {
	/**
	 * Stores details about the last profiled method
	 */
	private $details;
	
	public function __construct() {
		
	}
	
	/**
	 * Runs a method with the provided arguments, and 
	 * returns details about how long it took. Works
	 * with instance methods and static methods. 
	 * 
	 * @param classname string 
	 * @param methodname string
	 * @param methodargs array
	 * @param invocations int The number of times to call the method
	 * @return float average invocation duration in seconds
	 */
	public function profile($classname, $methodname, $methodargs, $invocations = 1) {
		if (class_exists($classname) != TRUE) {
			throw new Exception("{$classname} doesn't exist");
		}
		
		$method = new ReflectionMethod($classname, $methodname);
		
		$instance = NULL;
		if ( ! $method->isStatic()) 		{
			$class = new ReflectionClass($classname);
			$instance = $class->newInstance();
		}
		
		$durations = array();
		for ($i = 0; $i < $invocations; $i++) {
			$start = microtime(true);
			$method->invokeArgs($instance, $methodargs);
			$durations[] = microtime(true) - $start;
		}

		$duration['total'] = round(array_sum($durations), 4);
		$duration['average'] = round($duration['total'] / count($durations), 4);
		$duration['worst'] = round(max($durations), 4);	
		
		$this->details = array(	'class' => $classname,
							   	'method' => $methodname,
							   	'arguments' => $methodargs,
						 		'duration' => $duration,
								'invocations' => $invocations);
		
		return $duration['average'];	
	}
	
	/**
	 * Returns a string representing the last invoked  
	 * method, including any arguments
	 * @return string
	 */
	private function _invoked_method() {
		return "{$this->details['class']}::{$this->details['method']}(" .
			 join(", ", $this->details['arguments']) . ")"; 
	}
	
	/**
	 * Prints out details about the last profiled method
	 */
	public function print_details() {
		$methodString = $this->_invoked_method();
		$numInvoked = $this->details['invocations'];
		
		if ($numInvoked == 1) {
			echo "{$methodString} took {$this->details['duration']['average']}s\n";
		} else {
			echo "{$methodString} was invoked {$numInvoked} times\n";
			echo "Total duration:   {$this->details['duration']['total']}s\n";
			echo "Average duration: {$this->details['duration']['average']}s\n";
			echo "Worst duration:   {$this->details['duration']['worst']}s\n";
		}
	}
}

// End of file: ./system/core/profile.php 
