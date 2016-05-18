#!/usr/bin/env php
<?php
/**
 * Carpet
 *
 * PHP Version 5.5.0<
 *
 * @category Console tool
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link https://github.com/localgod Carpet
 * @since 2016-05-18
 */
if (! $loader = include __DIR__ . '/../vendor/autoload.php') {
    die('You must set up the project dependencies.');
}

$app = new \Cilex\Application('Cilex');
$app->register(new Localgod\Carpet\Provider\Jira\JiraServiceProvider(), [
    'jira.connection' => array(
        'protocol' => 'https://',
        'host' => 'example.com',
        'port' => 8080,
        'path' => '/rest/api/2',
        'user' => 'username',
        'pass' => 'password'
    )
]);
$app->command(new Localgod\Carpet\Command\GroupCommand($app['jira']));
$app->command(new Localgod\Carpet\Command\ProjectCommand($app['jira']));
$app->run();