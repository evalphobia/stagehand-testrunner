<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * PHP version 5.3
 *
 * Copyright (c) 2011 KUBO Atsuhiro <kubo@iteman.jp>,
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
 * @copyright  2011 KUBO Atsuhiro <kubo@iteman.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: @package_version@
 * @since      File available since Release 3.0.0
 */

namespace Stagehand\TestRunner\Test;

use Stagehand\TestRunner\Core\Configuration\GeneralConfiguration;

use Stagehand\TestRunner\Core\ApplicationContext;
use Stagehand\TestRunner\Core\ConfigurationTransformer;
use Stagehand\TestRunner\Core\Environment;
use Stagehand\TestRunner\Core\TestingFramework;

/**
 * @package    Stagehand_TestRunner
 * @copyright  2011 KUBO Atsuhiro <kubo@iteman.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: @package_version@
 * @since      Class available since Release 3.0.0
 */
class TestEnvironment extends Environment
{
    /**
     * @var \Stagehand\TestRunner\Core\ApplicationContext
     */
    private static $applicationContext;

    public static function earlyInitialize()
    {
        $oldApplicationContext = ApplicationContext::getInstance();

        $container = new TestContainerBuilder();
        $componentFactory = new TestComponentFactory();
        self::$applicationContext = new TestApplicationContext();
        self::$applicationContext->setComponentFactory($componentFactory);
        self::$applicationContext->setEnvironment(new TestEnvironment());
        ApplicationContext::setInstance(self::$applicationContext);
        $configurationTransformer = new ConfigurationTransformer($container);
        $configurationTransformer->setConfigurationPart(GeneralConfiguration::getConfigurationID(), array('testing_framework' => TestingFramework::PHPUNIT));
        ApplicationContext::getInstance()
            ->getComponentFactory()
            ->setContainer($configurationTransformer->transformToContainer());

        ApplicationContext::setInstance($oldApplicationContext);
    }

    /**
     * @return \Stagehand\TestRunner\Core\ApplicationContext
     */
    public static function getApplicationContext()
    {
        return self::$applicationContext;
    }

    /**
     * @return boolean
     */
    public function isProduction()
    {
        return false;
    }

    protected function initialize()
    {
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
