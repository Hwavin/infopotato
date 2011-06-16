<?php

define('RATE', 0.05);

function interest_per_year($amount){
  return round($amount * RATE, 2);
}

// Include test library
require_once '../simpletest/unit_tester.php' ;
require_once '../simpletest/reporter.php' ;

class TestingTestCase extends UnitTestCase{
  function TestInterest(){
    $this->assertEqual(5, interest_per_year(100));
  }
	
}

// run test
$test = new TestingTestCase( 'My First Unit Test' );
$test->run(new HTMLReporter());
