# Stagehand_TestRunner - A continuous test runner for CLI

Stagehand_TestRunner is a continuous test runner to run unit tests in CLI.

It makes unit testing in a local environment much easier and comfortable. Additionally it can easily be integrated with a continuous integration server like [Jenkins](http://jenkins-ci.org/). It has been designed for integration into Integrated Development Environments (IDEs), a developer can create an extension like [MakeGood](http://piece-framework.com/projects/makegood).

![Continuous Testing and Notification](https://github.com/piece/stagehand-testrunner/wiki/continuous_testing_and_notification_800.png)

h2. Features

* Runs tests when a file is saved. (**Continuous Testing**)
* Runs tests in the specified directory.
* Runs tests in the specified file.
* Runs only the specified tests in the specified file.
* Runs only the tests in the specified classes.
* Colors the output.
* Preloads the specified PHP script before running tests.
* Notifies test results.
* Logs test results into the specified file in the [JUnit](http://www.junit.org/) XML format.
* Prints detailed progress report.
* Stops on the first failure or error.
* Specifies the test file pattern.
* Uses a YAML-based configuration file.
* Runs the phpunit command via the testrunner command.
* Supports [CakePHP](http://cakephp.org/), [CIUnit](http://www.knollet.com/foostack/), [PHPSpec](http://www.phpspec.net/), [PHPUnit](https://github.com/sebastianbergmann/phpunit), and [SimpleTest](http://simpletest.org/).

## Installation

Stagehand_TestRunner can be installed using [Composer](http://getcomposer.org/) or [PEAR](http://pear.php.net/). The following sections explain how to install Stagehand_TestRunner.

### Composer

First, add the dependency to **piece/stagehand-testrunner** into your **composer.json** file as the following:

```json
{
    "require-dev": {
        "piece/stagehand-testrunner": ">=3.6.2"
    }
}
```

Second, update your dependencies as the following:

```console
composer update piece/stagehand-testrunner
```

### PEAR

```console
pear config-set auto_discover 1
pear install pear.piece-framework.com/Stagehand_TestRunner
```

## Support

If you find a bug or have a question, or want to request a feature, create an issue or pull request for it on [Issues](https://github.com/piece/stagehand-testrunner/issues).

## Copyright

Copyright (c) 2005-2013 KUBO Atsuhiro and [contributors](https://github.com/piece/stagehand-testrunner/wiki/Contributors), All rights reserved.

## License

[The BSD 2-Clause License](http://opensource.org/licenses/BSD-2-Clause)
