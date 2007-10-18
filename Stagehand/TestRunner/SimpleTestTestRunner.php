<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * PHP versions 4 and 5
 *
 * Copyright (c) 2007 Masahiko Sakamoto <msakamoto-sf@users.sourceforge.net>,
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    Stagehand_TestRunner
 * @copyright  2007 Masahiko Sakamoto <msakamoto-sf@users.sourceforge.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License (revised)
 * @version    SVN: $Id$
 * @see        http://simpletest.org/
 * @since      File available since Release 1.1.0
 */

require_once 'simpletest/test_case.php';
require_once 'simpletest/reporter.php';
require_once 'simpletest/scorer.php';
require_once 'PHP/Compat.php';

PHP_Compat::loadFunction('scandir');

// {{{ Stagehand_TestRunner_SimpleTestTestRunner

/**
 * A test runner for SimpleTest.
 *
 * @package    Stagehand_TestRunner
 * @copyright  2007 Masahiko Sakamoto <msakamoto-sf@users.sourceforge.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License (revised)
 * @version    Release: @package_version@
 * @see        http://simpletest.org/
 * @since      Class available since Release 1.1.0
 */
class Stagehand_TestRunner_SimpleTestTestRunner
{

    // {{{ properties

    /**#@+
     * @access public
     */

    /**#@-*/

    /**#@+
     * @access private
     */

    /**#@-*/

    /**#@+
     * @access public
     * @static
     */

    // }}}
    // {{{ run()

    /**
     * Runs target tests in the directory.
     *
     * @param string $directory
     * @param string $excludePattern
     * @return mixed
     */
    function run($directory, $excludePattern = '!^unittestcase$!')
    {
        eval('$suite = ' . '&' .  __CLASS__ . "::_getTestSuite('$directory', '$excludePattern');");
        $reporter = &new TextReporter();
        $suite->run($reporter);
        return $reporter;
    }

    // }}}
    // {{{ runAll()

    /**
     * Runs all tests under the directory.
     *
     * @param string $directory
     * @param string $excludePattern
     * @return mixed
     */
    function runAll($directory, $excludePattern = '!^unittestcase$!')
    {
        $suite = &new TestSuite();
        eval('$directories = ' . __CLASS__ . "::getDirectories('$directory');");

        for ($i = 0, $count = count($directories); $i < $count; ++$i) {
            eval('$test = ' . '&' . __CLASS__ . "::_getTestSuite('$directories[$i]', '$excludePattern');");
            if (!$test->getSize()) {
                continue;
            }
            $suite->addTestCase($test);
        }

        $reporter = &new SimpleReporter();
        $suite->run($reporter);
        return $reporter;
    }

    // }}}
    // {{{ getDirectories()

    /**
     * Returns all directories under the directory.
     *
     * @param string $directory
     * @return array
     */
    function getDirectories($directory)
    {
        static $directories;
        if (is_null($directories)) {
            $directories = array();
        }

        $directory = realpath($directory);
        $directories[] = $directory;
        $files = scandir($directory);

        for ($i = 0, $count = count($files); $i < $count; ++$i) {
            if ($files[$i] == '.' || $files[$i] == '..') {
                continue;
            }

            $next = $directory . DIRECTORY_SEPARATOR . $files[$i];
            if (!is_dir($next)) {
                continue;
            }

            call_user_func(array(__CLASS__, 'getDirectories'), $next);
        }

        return $directories;
    }

    /**#@-*/

    /**#@+
     * @access private
     * @static
     */

    // }}}
    // {{{ _getTestSuite()

    /**
     * Returns the test suite that contains all of the test cases in the
     * directory.
     *
     * @param string $directory
     * @param string $excludePattern
     * @return PHPUnit_TestSuite
     */
    function &_getTestSuite($directory, $excludePattern = '!^unittestcase$!')
    {
        $directory = realpath($directory);
        eval('$testCases = ' . __CLASS__ . "::_getTestCases('$directory', '$excludePattern');");
        $suite = &new TestSuite();

        for ($i = 0, $count = count($testCases); $i < $count; ++$i) {
            $suite->addTestClass($testCases[$i]); // TODO NOT addTestCases()?
        }

        return $suite;
    }

    // }}}
    // {{{ _getTestCases()

    /**
     * Returns target test cases in the directory.
     *
     * @param string $directory
     * @param string $excludePattern
     * @return array
     */
    function _getTestCases($directory, $excludePattern = '!^unittestcase$!')
    {
        $testCases = array();
        if (is_dir($directory)) {
            $files = scandir($directory);
        } else {
            $files = (array)$directory;
        }

        for ($i = 0, $iCount = count($files); $i < $iCount; ++$i) {
            if (is_dir($directory)) {
                $target = $directory . DIRECTORY_SEPARATOR . $files[$i];
            } else {
                $target = $files[$i];
            }

            if (!is_file($target)) {
                continue;
            }

            if (!preg_match('/TestCase\.php$/', $files[$i])) {
                continue;
            }

            print "Loading [ {$files[$i]} ] ... ";

            $currentClasses = get_declared_classes();

            if (!include_once($target)) {
                print "Failed!\n";
                continue;
            }

            print "Succeeded.\n";

            $newClasses = array_values(array_diff(get_declared_classes(), $currentClasses));
            for ($j = 0, $jCount = count($newClasses); $j < $jCount; ++$j) {
                eval('$exclude = ' . __CLASS__ . "::_exclude('$newClasses[$j]', '$excludePattern');");
                if ($exclude) {
                    continue;
                }

                $testCases[] = $newClasses[$j];
                print "  => Added [ {$newClasses[$j]} ]\n";
            }
        }

        return $testCases;
    }

    // }}}
    // {{{ _exclude()

    /**
     * Returns whether the class should be exclude or not.
     *
     * @param string $class
     * @param string $excludePattern
     * @return boolean
     */
    function _exclude($class, $excludePattern = '!^unittestcase$!')
    {
        if (!preg_match('/TestCase$/i', $class)) {
            return true;
        }

        if (strlen($excludePattern) && preg_match($excludePattern, $class)) {
            return true;
        }

        $test = new $class();
        return !is_a($test, 'UnitTestCase');
    }

    /**#@-*/

    // }}}
}

// }}}

/*
 * Local Variables:
 * mode: php
 * coding: iso-8859-1
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * indent-tabs-mode: nil
 * End:
 */
?>