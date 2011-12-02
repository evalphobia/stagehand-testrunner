<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * PHP version 5.3
 *
 * Copyright (c) 2008-2011 KUBO Atsuhiro <kubo@iteman.jp>,
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
 * @copyright  2008-2011 KUBO Atsuhiro <kubo@iteman.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: @package_version@
 * @since      File available since Release 2.3.0
 */

namespace Stagehand\TestRunner\Runner;

use Stagehand\TestRunner\CLI\Terminal;
use Stagehand\TestRunner\Core\TestTargets;

/**
 * The base class for test runners.
 *
 * @package    Stagehand_TestRunner
 * @copyright  2008-2011 KUBO Atsuhiro <kubo@iteman.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: @package_version@
 * @since      Class available since Release 2.3.0
 */
abstract class Runner
{
    /**
     * @var \Stagehand\TestRunner\Notification\Notification
     */
    protected $notification;

    /**
     * @var \Stagehand\TestRunner\CLI\Terminal
     * @since Property available since Release 3.0.0
     */
    protected $terminal;

    /**
     * @var string
     * @since Property available since Release 3.0.0
     */
    protected $junitXMLFile;

    /**
     * @var boolean
     * @since Property available since Release 3.0.0
     */
    protected $logsResultsInJUnitXML;

    /**
     * @var boolean
     * @since Property available since Release 3.0.0
     */
    protected $logsResultsInJUnitXMLInRealtime;

    /**
     * @var boolean
     * @since Property available since Release 3.0.0
     */
    protected $stopsOnFailure;

    /**
     * @var boolean
     * @since Property available since Release 3.0.0
     */
    protected $usesNotification;

    /**
     * @var \Stagehand\TestRunner\Core\TestTargets
     * @since Property available since Release 3.0.0
     */
    protected $testTargets;

    /**
     * Runs tests.
     *
     * @param mixed $suite
     */
    abstract public function run($suite);

    /**
     * Gets a notification object for Growl.
     *
     * @return \Stagehand\TestRunner\Notification\Notification
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param \Stagehand\TestRunner\CLI\Terminal $terminal
     * @since Method available since Release 3.0.0
     */
    public function setTerminal(Terminal $terminal)
    {
        $this->terminal = $terminal;
    }

    /**
     * @param string $junitXMLFile
     * @since Method available since Release 3.0.0
     */
    public function setJUnitXMLFile($junitXMLFile)
    {
        if (is_null($junitXMLFile)) {
            $this->logsResultsInJUnitXML = false;
        } else {
            $this->junitXMLFile = $junitXMLFile;
            $this->logsResultsInJUnitXML = true;
        }
    }

    /**
     * @param boolean $logsResultsInJUnitXMLInRealtime
     * @since Method available since Release 3.0.0
     */
    public function setLogsResultsInJUnitXMLInRealtime($logsResultsInJUnitXMLInRealtime)
    {
        $this->logsResultsInJUnitXMLInRealtime = $logsResultsInJUnitXMLInRealtime;
    }

    /**
     * @param boolean $stopsOnFailure
     * @since Method available since Release 3.0.0
     */
    public function setStopsOnFailure($stopsOnFailure)
    {
        $this->stopsOnFailure = $stopsOnFailure;
    }

    /**
     * @return boolean
     * @since Method available since Release 3.0.0
     */
    public function stopsOnFailure()
    {
        return $this->stopsOnFailure;
    }

    /**
     * @param boolean $usesNotification
     * @since Method available since Release 3.0.0
     */
    public function setUsesNotification($usesNotification)
    {
        $this->usesNotification = $usesNotification;
    }

    /**
     * @return boolean
     * @since Method available since Release 3.0.0
     */
    public function usesNotification()
    {
        return $this->usesNotification;
    }

    /**
     * @param \Stagehand\TestRunner\Core\TestTargets $testTargets
     * @since Property available since Release 3.0.0
     */
    public function setTestTargets(TestTargets $testTargets)
    {
        $this->testTargets = $testTargets;
    }
}

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