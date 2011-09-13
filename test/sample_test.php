<?php
include(dirname(__FILE__).'/../system/core/lime.php');

$t = new lime_test(8);
 
// strtolower()
$t->diag('strtolower()');
$t->isa_ok(strtolower('Foo'), 'string',
    'strtolower() returns a string');
$t->is(strtolower('FOO'), 'foo',
    'strtolower() transforms the input to lowercase');
$t->is(strtolower('foo'), 'foo',
    'strtolower() leaves lowercase characters unchanged');
$t->is(strtolower('12#?@~'), '12#?@~',
    'strtolower() leaves non alphabetical characters unchanged');
$t->is(strtolower('FOO BAR'), 'foo bar',
    'strtolower() leaves blanks alone');
$t->is(strtolower('FoO bAr'), 'foo bar',
    'strtolower() deals with mixed case input');
$t->is(strtolower(''), 'foo',
    'strtolower() transforms empty strings into foo');

$t->ok(1+1==2,
    'yes, passed');

	
	
	
	
	
	
	
	
	
	
	
	// Stub objects and functions for test purposes
class myObject
{
  public function myMethod()
  {
  }
}
 
function throw_an_exception()
{
  throw new Exception('exception thrown');
}
 
// Initialize the test object
$t = new lime_test(16);
 
$t->diag('hello world');
$t->ok(1 == '1', 'the equal operator ignores type');
$t->is(1, '1', 'a string is converted to a number for comparison');
$t->isnt(0, 1, 'zero and one are not equal');
$t->like('test01', '/test\d+/', 'test01 follows the test numbering pattern');
$t->unlike('tests01', '/test\d+/', 'tests01 does not follow the pattern');
$t->cmp_ok(1, '<', 2, 'one is inferior to two');
$t->cmp_ok(1, '!==', true, 'one and true are not identical');
$t->isa_ok('foobar', 'string', '\'foobar\' is a string');
$t->isa_ok(new myObject(), 'myObject', 'new creates object of the right class');
$t->can_ok(new myObject(), 'myMethod', 'objects of class myObject do have a myMethod method');
$array1 = array(1, 2, array(1 => 'foo', 'a' => '4'));
$t->is_deeply($array1, array(1, 2, array(1 => 'foo', 'a' => '4')),
    'the first and the second array are the same');
$t->include_ok('./fooBar.php', 'the fooBar.php file was properly included');
 
try
{
  throw_an_exception();
  $t->fail('no code should be executed after throwing an exception');
}
catch (Exception $e)
{
  $t->pass('exception caught successfully');
}
 
if (!isset($foobar))
{
  $t->skip('skipping one test to keep the test count exact in the condition', 1);
}
else
{
  $t->ok($foobar, 'foobar');
}
 
$t->todo('one test left to do');

/* End of file: ./test/functions/redirect_function_test.php */