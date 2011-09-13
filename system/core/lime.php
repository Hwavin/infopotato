<?php
/**
 * Unit test library v1.4.
 *
 * @package    lime
 * @author     Fabien Potencier <fabien.potencier@gmail.com>
 * @version    SVN: $Id: lime.php 29529 2010-05-19 13:41:48Z fabien $
 */
class lime_test {
    const EPSILON = 0.0000000001;

    protected $test_nb = 0;
    protected $output  = NULL;
    protected $results = array();
    protected $options = array();

    static protected $all_results = array();

    public function __construct($plan = NULL, $options = array()) {
        $this->options = array_merge(array(
            'force_colors'    => FALSE,
            'output'          => NULL,
            'verbose'         => FALSE,
            'error_reporting' => FALSE,
        ), $options);

        $this->output = $this->options['output'] ? $this->options['output'] : new lime_output($this->options['force_colors']);

        $caller = $this->find_caller(debug_backtrace());
        self::$all_results[] = array(
            'file'  => $caller[0],
            'tests' => array(),
            'stats' => array('plan' => $plan, 'total' => 0, 'failed' => array(), 'passed' => array(), 'skipped' => array(), 'errors' => array()),
        );

        $this->results = &self::$all_results[count(self::$all_results) - 1];

        NULL !== $plan and $this->output->echoln(sprintf("1..%d", $plan));

        set_error_handler(array($this, 'handle_error'));
        set_exception_handler(array($this, 'handle_exception'));
    }

    static public function reset() {
        self::$all_results = array();
    }

    public function __destruct() {
        $plan = $this->results['stats']['plan'];
        $passed = count($this->results['stats']['passed']);
        $failed = count($this->results['stats']['failed']);
        $total = $this->results['stats']['total'];
        is_null($plan) and $plan = $total and $this->output->echoln(sprintf("1..%d", $plan));

        if ($total > $plan) {
            $this->output->red_bar(sprintf("# Looks like you planned %d tests but ran %d extra.", $plan, $total - $plan));
        } elseif ($total < $plan) {
            $this->output->red_bar(sprintf("# Looks like you planned %d tests but only ran %d.", $plan, $total));
        }

        if ($failed) {
            $this->output->red_bar(sprintf("# Looks like you failed %d tests of %d.", $failed, $passed + $failed));
        } elseif ($total == $plan) {
            $this->output->green_bar("# Looks like everything went fine.");
        }

         flush();
    }

    /**
     * Tests a condition and passes if it is true
     *
     * @param mixed  $exp     condition to test
     * @param string $message display output message when the test passes
     *
     * @return boolean
     */
    public function ok($exp, $message = '') {
        $this->update_stats();

        if ($result = (boolean) $exp) {
            $this->results['stats']['passed'][] = $this->test_nb;
        } else {
            $this->results['stats']['failed'][] = $this->test_nb;
        }
        $this->results['tests'][$this->test_nb]['message'] = $message;
        $this->results['tests'][$this->test_nb]['status'] = $result;
        $this->output->echoln(sprintf("%s %d%s", $result ? 'ok' : 'not ok', $this->test_nb, $message = $message ? sprintf('%s %s', 0 === strpos($message, '#') ? '' : ' -', $message) : ''));

        if ( ! $result) {
            $this->output->diag(sprintf('    Failed test (%s at line %d)', str_replace(getcwd(), '.', $this->results['tests'][$this->test_nb]['file']), $this->results['tests'][$this->test_nb]['line']));
        }

        return $result;
    }

    /**
     * Compares two values and passes if they are equal (==)
     *
     * @param mixed  $exp1    left value
     * @param mixed  $exp2    right value
     * @param string $message display output message when the test passes
     *
     * @return boolean
     */
    public function is($exp1, $exp2, $message = '') {
        if (is_object($exp1) || is_object($exp2)) {
            $value = $exp1 === $exp2;
        } else if (is_float($exp1) && is_float($exp2)) {
            $value = abs($exp1 - $exp2) < self::EPSILON;
        } else {
            $value = $exp1 == $exp2;
        }

        if ( ! $result = $this->ok($value, $message)) {
            $this->set_last_test_errors(array(sprintf("           got: %s", var_export($exp1, TRUE)), sprintf("      expected: %s", var_export($exp2, TRUE))));
        }

        return $result;
    }

    /**
     * Compares two values and passes if they are not equal
     *
     * @param mixed  $exp1    left value
     * @param mixed  $exp2    right value
     * @param string $message display output message when the test passes
     *
     * @return boolean
     */
    public function isnt($exp1, $exp2, $message = '') {
        if ( ! $result = $this->ok($exp1 != $exp2, $message)) {
            $this->set_last_test_errors(array(sprintf("      %s", var_export($exp2, TRUE)), '          ne', sprintf("      %s", var_export($exp2, TRUE))));
        }

        return $result;
    }

    /**
     * Tests a string against a regular expression
     *
     * @param string $exp     value to test
     * @param string $regex   the pattern to search for, as a string
     * @param string $message display output message when the test passes
     *
     * @return boolean
     */
    public function like($exp, $regex, $message = '') {
        if ( ! $result = $this->ok(preg_match($regex, $exp), $message)) {
            $this->set_last_test_errors(array(sprintf("                    '%s'", $exp), sprintf("      doesn't match '%s'", $regex)));
        }

        return $result;
    }

    /**
     * Checks that a string doesn't match a regular expression
     *
     * @param string $exp     value to test
     * @param string $regex   the pattern to search for, as a string
     * @param string $message display output message when the test passes
     *
     * @return boolean
     */
    public function unlike($exp, $regex, $message = '') {
        if (!$result = $this->ok(!preg_match($regex, $exp), $message)) {
            $this->set_last_test_errors(array(sprintf("               '%s'", $exp), sprintf("      matches '%s'", $regex)));
        }

        return $result;
    }

    /**
     * Compares two arguments with an operator
     *
     * @param mixed  $exp1    left value
     * @param string $op      operator
     * @param mixed  $exp2    right value
     * @param string $message display output message when the test passes
     *
     * @return boolean
     */
    public function cmp_ok($exp1, $op, $exp2, $message = '') {
        $php = sprintf("\$result = \$exp1 $op \$exp2;");
        // under some unknown conditions the sprintf() call causes a segmentation fault
        // when placed directly in the eval() call
        eval($php);

        if ( ! $this->ok($result, $message)) {
            $this->set_last_test_errors(array(sprintf("      %s", str_replace("\n", '', var_export($exp1, TRUE))), sprintf("          %s", $op), sprintf("      %s", str_replace("\n", '', var_export($exp2, TRUE)))));
        }

        return $result;
    }

    /**
     * Checks the availability of a method for an object or a class
     *
     * @param mixed        $object  an object instance or a class name
     * @param string|array $methods one or more method names
     * @param string       $message display output message when the test passes
     *
     * @return boolean
     */
    public function can_ok($object, $methods, $message = '') {
        $result = TRUE;
        $failed_messages = array();
        foreach ((array) $methods as $method) {
            if ( ! method_exists($object, $method)) {
                $failed_messages[] = sprintf("      method '%s' does not exist", $method);
                $result = FALSE;
            }
        }

        !$this->ok($result, $message);

        !$result and $this->set_last_test_errors($failed_messages);

        return $result;
    }

    /**
     * Checks the type of an argument
     *
     * @param mixed  $var     variable instance
     * @param string $class   class or type name
     * @param string $message display output message when the test passes
     *
     * @return boolean
     */
    public function isa_ok($var, $class, $message = '') {
        $type = is_object($var) ? get_class($var) : gettype($var);
        if ( ! $result = $this->ok($type == $class, $message)) {
            $this->set_last_test_errors(array(sprintf("      variable isn't a '%s' it's a '%s'", $class, $type)));
        }

        return $result;
    }

    /**
     * Checks that two arrays have the same values
     *
     * @param mixed  $exp1    first variable
     * @param mixed  $exp2    second variable
     * @param string $message display output message when the test passes
     *
     * @return boolean
     */
    public function is_deeply($exp1, $exp2, $message = '') {
        if ( ! $result = $this->ok($this->_test_is_deeply($exp1, $exp2), $message)) {
            $this->set_last_test_errors(array(sprintf("           got: %s", str_replace("\n", '', var_export($exp1, TRUE))), sprintf("      expected: %s", str_replace("\n", '', var_export($exp2, TRUE)))));
        }

        return $result;
    }

    /**
     * Always passes--useful for testing exceptions
     *
     * @param string $message display output message
     *
     * @return TRUE
     */
    public function pass($message = '') {
        return $this->ok(TRUE, $message);
    }

    /**
     * Always fails--useful for testing exceptions
     *
     * @param string $message display output message
     *
     * @return FALSE
     */
    public function fail($message = '') {
        return $this->ok(FALSE, $message);
    }

    /**
     * Outputs a diag message but runs no test
     *
     * @param string $message display output message
     *
     * @return void
     */
    public function diag($message) {
        $this->output->diag($message);
    }

    /**
     * Counts as $nb_tests tests--useful for conditional tests
     *
     * @param string  $message  display output message
     * @param integer $nb_tests number of tests to skip
     *
     * @return void
     */
    public function skip($message = '', $nb_tests = 1) {
        for ($i = 0; $i < $nb_tests; $i++) {
            $this->pass(sprintf("# SKIP%s", $message ? ' '.$message : ''));
            $this->results['stats']['skipped'][] = $this->test_nb;
            array_pop($this->results['stats']['passed']);
        }
    }

    /**
     * Counts as a test--useful for tests yet to be written
     *
     * @param string $message display output message
     *
     * @return void
     */
    public function todo($message = '') {
        $this->pass(sprintf("# TODO%s", $message ? ' '.$message : ''));
        $this->results['stats']['skipped'][] = $this->test_nb;
        array_pop($this->results['stats']['passed']);
    }

    /**
     * Validates that a file exists and that it is properly included
     *
     * @param string $file    file path
     * @param string $message display output message when the test passes
     *
     * @return boolean
     */
    public function include_ok($file, $message = '') {
        if ( ! $result = $this->ok((@include($file)) == 1, $message)) {
            $this->set_last_test_errors(array(sprintf("      Tried to include '%s'", $file)));
        }

        return $result;
    }

    private function _test_is_deeply($var1, $var2) {
        if (gettype($var1) != gettype($var2)) {
            return FALSE;
        }

        if (is_array($var1)) {
            ksort($var1);
            ksort($var2);

            $keys1 = array_keys($var1);
            $keys2 = array_keys($var2);
            if (array_diff($keys1, $keys2) || array_diff($keys2, $keys1)) {
                return FALSE;
            }
            $is_equal = TRUE;
            foreach ($var1 as $key => $value) {
                $is_equal = $this->_test_is_deeply($var1[$key], $var2[$key]);
                if ($is_equal === FALSE) {
                     break;
                }
            }

            return $is_equal;
        } else {
            return $var1 === $var2;
        }
    }

    public function comment($message) {
        $this->output->comment($message);
    }

    public function info($message) {
        $this->output->info($message);
    }

    public function error($message, $file = NULL, $line = NULL, $traces = array()) {
        $this->output->error($message, $file, $line, $traces);

  	    $this->results['stats']['errors'][] = array(
  	        'message' => $message,
  	        'file' => $file,
  	        'line' => $line,
  	    );
    }

    protected function update_stats() {
        ++$this->test_nb;
        ++$this->results['stats']['total'];

        list($this->results['tests'][$this->test_nb]['file'], $this->results['tests'][$this->test_nb]['line']) = $this->find_caller(debug_backtrace());
    }

    protected function set_last_test_errors($errors = array()) {
        $this->output->diag($errors);

        $this->results['tests'][$this->test_nb]['error'] = implode("\n", $errors);
    }

    protected function find_caller($traces) {
        // find the first call to a method of an object that is an instance of lime_test
        $t = array_reverse($traces);
        foreach ($t as $trace) {
            if (isset($trace['object']) && $trace['object'] instanceof lime_test) {
                return array($trace['file'], $trace['line']);
            }
        }

        // return the first call
        $last = count($traces) - 1;
        return array($traces[$last]['file'], $traces[$last]['line']);
    }

    public function handle_error($code, $message, $file, $line, $context) {
        if ( ! $this->options['error_reporting'] || ($code & error_reporting()) == 0)  {
            return FALSE;
        }

        switch ($code) {
            case E_WARNING:
                $type = 'Warning';
                break;
            
			default:
                $type = 'Notice';
                break;
        }

        $trace = debug_backtrace();
        array_shift($trace); // remove the handle_error() call from the trace

        $this->error($type.': '.$message, $file, $line, $trace);
    }

    public function handle_exception(Exception $exception) {
        $this->error(get_class($exception).': '.$exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTrace());

        // exception was handled
        return TRUE;
    }
}

class lime_output {
    public $colorizer = NULL;
    public $base_dir = NULL;

    public function __construct($force_colors = FALSE, $base_dir = NULL) {
        $this->colorizer = new lime_colorizer($force_colors);
        $this->base_dir = $base_dir === NULL ? getcwd() : $base_dir;
    }

    public function diag() {
        $messages = func_get_args();
        foreach ($messages as $message) {
            echo $this->colorizer->colorize('# '.join("\n# ", (array) $message), 'COMMENT')."\n";
        }
    }

    public function comment($message) {
        echo $this->colorizer->colorize(sprintf('# %s', $message), 'COMMENT')."\n";
    }

    public function info($message) {
        echo $this->colorizer->colorize(sprintf('> %s', $message), 'INFO_BAR')."\n";
    }

    public function error($message, $file = NULL, $line = NULL, $traces = array()) {
        if ($file !== NULL) {
            $message .= sprintf("\n(in %s on line %s)", $file, $line);
        }

        // some error messages contain absolute file paths
        $message = $this->strip_base_dir($message);

        $space = $this->colorizer->colorize(str_repeat(' ', 71), 'RED_BAR')."\n";
        $message = trim($message);
        $message = wordwrap($message, 66, "\n");

        echo "\n".$space;
        foreach (explode("\n", $message) as $message_line) {
            echo $this->colorizer->colorize(str_pad('  '.$message_line, 71, ' '), 'RED_BAR')."\n";
        }
        echo $space."\n";

        if (count($traces) > 0) {
            echo $this->colorizer->colorize('Exception trace:', 'COMMENT')."\n";

            $this->print_trace(NULL, $file, $line);

            foreach ($traces as $trace) {
                if (array_key_exists('class', $trace)) {
                    $method = sprintf('%s%s%s()', $trace['class'], $trace['type'], $trace['function']);
                } else {
                    $method = sprintf('%s()', $trace['function']);
                }

                if (array_key_exists('file', $trace)) {
                    $this->print_trace($method, $trace['file'], $trace['line']);
                } else {
                    $this->print_trace($method);
                }
            }

            echo "\n";
        }
    }

    protected function print_trace($method = NULL, $file = NULL, $line = NULL) {
        if ( ! is_null($method)) {
            $method .= ' ';
        }

        echo '  '.$method.'at ';

        if ( ! is_null($file) && ! is_null($line)) {
            printf("%s:%s\n", $this->colorizer->colorize($this->strip_base_dir($file), 'TRACE'), $this->colorizer->colorize($line, 'TRACE'));
        } else {
            echo "[internal function]\n";
        }
    }

    public function echoln($message, $colorizer_parameter = NULL, $colorize = TRUE) {
        if ($colorize) {
            $message = preg_replace('/(?:^|\.)((?:not ok|dubious|errors) *\d*)\b/e', '$this->colorizer->colorize(\'$1\', \'ERROR\')', $message);
            $message = preg_replace('/(?:^|\.)(ok *\d*)\b/e', '$this->colorizer->colorize(\'$1\', \'INFO\')', $message);
            $message = preg_replace('/"(.+?)"/e', '$this->colorizer->colorize(\'$1\', \'PARAMETER\')', $message);
            $message = preg_replace('/(\->|\:\:)?([a-zA-Z0-9_]+?)\(\)/e', '$this->colorizer->colorize(\'$1$2()\', \'PARAMETER\')', $message);
        }

        echo ($colorizer_parameter ? $this->colorizer->colorize($message, $colorizer_parameter) : $message)."\n";
    }

    public function green_bar($message) {
        echo $this->colorizer->colorize($message.str_repeat(' ', 71 - min(71, strlen($message))), 'GREEN_BAR')."\n";
    }

    public function red_bar($message) {
        echo $this->colorizer->colorize($message.str_repeat(' ', 71 - min(71, strlen($message))), 'RED_BAR')."\n";
    }

    protected function strip_base_dir($text) {
        return str_replace(DIRECTORY_SEPARATOR, '/', str_replace(realpath($this->base_dir).DIRECTORY_SEPARATOR, '', $text));
    }
}

class lime_output_color extends lime_output {}

class lime_colorizer {
    static public $styles = array();

    protected $colors_supported = FALSE;

    public function __construct($force_colors = FALSE) {
        if ($force_colors) {
            $this->colors_supported = TRUE;
        } else {
            // colors are supported on windows with ansicon or on tty consoles
            if (DIRECTORY_SEPARATOR == '\\') {
                 $this->colors_supported = FALSE !== getenv('ANSICON');
            } else {
                $this->colors_supported = function_exists('posix_isatty') && @posix_isatty(STDOUT);
            }
        }
    }

    public static function style($name, $options = array()) {
        self::$styles[$name] = $options;
    }

    public function colorize($text = '', $parameters = array()) {

        if ( ! $this->colors_supported) {
            return $text;
        }

        static $options    = array('bold' => 1, 'underscore' => 4, 'blink' => 5, 'reverse' => 7, 'conceal' => 8);
        static $foreground = array('black' => 30, 'red' => 31, 'green' => 32, 'yellow' => 33, 'blue' => 34, 'magenta' => 35, 'cyan' => 36, 'white' => 37);
        static $background = array('black' => 40, 'red' => 41, 'green' => 42, 'yellow' => 43, 'blue' => 44, 'magenta' => 45, 'cyan' => 46, 'white' => 47);

        !is_array($parameters) && isset(self::$styles[$parameters]) and $parameters = self::$styles[$parameters];

        $codes = array();
        isset($parameters['fg']) and $codes[] = $foreground[$parameters['fg']];
        isset($parameters['bg']) and $codes[] = $background[$parameters['bg']];
        foreach ($options as $option => $value) {
            isset($parameters[$option]) && $parameters[$option] and $codes[] = $value;
        }

        return "\033[".implode(';', $codes).'m'.$text."\033[0m";
    }
}

lime_colorizer::style('ERROR', array('bg' => 'red', 'fg' => 'white', 'bold' => TRUE));
lime_colorizer::style('INFO', array('fg' => 'green', 'bold' => TRUE));
lime_colorizer::style('TRACE', array('fg' => 'green', 'bold' => TRUE));
lime_colorizer::style('PARAMETER', array('fg' => 'cyan'));
lime_colorizer::style('COMMENT', array('fg' => 'yellow'));

lime_colorizer::style('GREEN_BAR', array('fg' => 'white', 'bg' => 'green', 'bold' => TRUE));
lime_colorizer::style('RED_BAR', array('fg' => 'white', 'bg' => 'red', 'bold' => TRUE));
lime_colorizer::style('INFO_BAR', array('fg' => 'cyan', 'bold' => TRUE));
