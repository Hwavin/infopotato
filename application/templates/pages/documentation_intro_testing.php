<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading"><?php echo __('Testing'); ?></h1>	
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home"><?php echo __('Home'); ?></a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/"><?php echo __('Documentation'); ?></a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/intro/">Introduction</a> &gt; <?php echo __('Testing'); ?>
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/intro/testing/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
Whenever you write a new line of code, you also potentially add new bugs. Automated tests should have you covered and this tutorial shows you how to write unit and functional tests for your InfoPotato application.
</p>

<p>
The idea is to create automatable tests for each bit of functionality that you are developing (at the same time you are developing it). This not only helps everyone later test that the software works, but helps the development itself, because it forces you to work in a modular way with very clearly defined structures and goals.
</p>

<p>
Unit tests confirm that a unitary code component provides the correct output for a given input. They validate how functions and methods work in every particular case. Unit tests deal with one case at a time, so for instance a single method may need several unit tests if it works differently in certain situations.
</p>

<p>
Writing unit tests is perhaps one of the hardest web development best practices to put into action. As web developers are not really used to testing their work, a lot of questions arise: Do I have to write tests before implementing a feature? What do I need to test? Do my tests need to cover every single edge case|Edge Cases? How can I be sure that everything is well tested? But usually, the first question is much more basic: Where to start?
</p>

<p>
It's always better to have some tests than no test at all. Do you already have a lot of code without any test? No problem. You don't need to have a full test suite to benefit from the advantages of having tests. Start by adding tests whenever you find a bug in your code. Over time, your code will become better, the code coverage|Code Coverage will rise, and you will become more confident about it. By starting with a pragmatic approach, you will feel more comfortable with tests over time. The next step is to write tests for new features. In no time, you will become a test addict.
</p>

<p>
There are many unit test frameworks in the PHP world, with the most well known being PhpUnit and SimpleTest. The problem with most testing libraries is their steep learning curve. InfoPotato uses Symfony's lime as unit testing tool to make writing test insanely easy. Lime is based on the Test::More Perl library, and is TAP compliant, which means that the result of tests is displayed as specified in the Test Anything Protocol, designed for better readability of test output.
</p>

<p>
Lime provides support for unit testing. It is more lightweight than other PHP testing frameworks and has several advantages:
</p>

<ul> 
<li>It launches test files in a sandbox to avoid strange side effects between each test run. Not all testing frameworks guarantee a clean environment for each test.</li> 
<li>Lime tests are very readable, and so is the test output. On compatible systems, lime uses color output in a smart way to distinguish important information.</li> 
<li>Symfony itself uses lime tests for regression testing, so many examples of unit and functional tests can be found in the symfony source code.</li> 
<li>The lime core is validated by unit tests.</li> 
<li>It is written in PHP, and it is fast and well coded. It is contained in a single file, <code>lime.php</code>, without any dependence.</li> 
</ul> 

<p>
Testing works by calling a method or a function with a set of predefined inputs and then comparing the results with the expected output. This comparison determines whether a test passes or fails.
</p>

<p>
To ease the comparison, the lime_test object provides several methods:
</p> 
 
<table cellspacing="0" class="grid"> 
<thead> 
<tr> 
  <th>Method</th> 
  <th>Description</th> 
</tr> 
</thead> 
<tbody> 
<tr> 
  <td><code>ok($test)</code></td> 
  <td>Tests a condition and passes if it is true</td> 
</tr> 
<tr> 
  <td><code>is($value1, $value2)</code></td> 
  <td>Compares two values and passes if they are equal (<code>==</code>)</td> 
</tr> 
<tr> 
  <td><code>isnt($value1, $value2)</code></td> 
  <td>Compares two values and passes if they are not equal</td> 
</tr> 
<tr> 
  <td><code>like($string, $regexp)</code></td> 
  <td>Tests a string against a regular expression</td> 
</tr> 
<tr> 
  <td><code>unlike($string, $regexp)</code></td> 
  <td>Checks that a string doesn't match a regular expression</td> 
</tr> 
<tr> 
  <td><code>is_deeply($array1, $array2)</code></td> 
  <td>Checks that two arrays have the same values</td> 
</tr> 
</tbody> 
</table> 

<p class="notebox"> 
  You may wonder why lime defines so many test methods, as all tests can be
  written just by using the <code>ok()</code> method. The benefit of alternative methods
  lies in much more explicit error messages in case of a failed test and in 
  improved readability of the tests.
  </p> 
 
<p>
The lime_test object also provides other convenient testing methods:
</p>

<table cellspacing="0" class="grid"> 
<thead> 
<tr> 
  <th>Method</th> 
  <th>Description</th> 
</tr> 
</thead> 
<tbody> 
<tr> 
  <td><code>fail()</code></td> 
  <td>Always fails--useful for testing exceptions</td> 
</tr> 
<tr> 
  <td><code>pass()</code></td> 
  <td>Always passes--useful for testing exceptions</td> 
</tr> 
<tr> 
  <td><code>skip($msg, $nb_tests)</code></td> 
  <td>Counts as <code>$nb_tests</code> tests--useful for conditional tests</td> 
</tr> 
<tr> 
  <td><code>todo()</code></td> 
  <td>Counts as a test--useful for tests yet to be written</td> 
</tr> 
</tbody> 
</table> 

<p>
Finally, the <code>comment($msg)</code> method outputs a comment but runs no test.
</p> 

<p>
The syntax is quite straightforward; notice that most methods take a message as their last parameter. This message is displayed in the output when the test passes. Actually, the best way to learn these methods is to test them.
</p>

<p>
The <code>diag()</code> method doesn't count as a test. Use it to show comments, so that your test output stays organized and legible. On the other hand, the <code>todo()</code> and <code>skip()</code> methods count as actual tests. A <code>pass()</code>/<code>fail()</code> combination inside a <code>try</code>/<code>catch</code> block counts as a single test.
</p> 

<p>
A well-planned test strategy must contain an expected number of tests. You will find it very useful to validate your own test files--especially in complex cases where tests are run inside conditions or exceptions. And if the test fails at some point, you will see it quickly because the final number of run tests won't match the number given during initialization.
</p>

<p class="notebox">
Each time you add tests, don't forget to update the number of expected tests (the plan) in the lime_test constructor method. 
</p>

<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>