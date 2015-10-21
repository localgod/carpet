<?php
/**
 * Carpet
 *
 * PHP Version 5.5.0<
 *
 * @category Console tool
 * @author   Johannes Skov Frandsen <localgod@heaven.dk>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/localgod Carpet
 * @since    2015-10-21
 */
namespace Localgod\Carpet\Provider\Jira;

use Cilex\Application;
use Cilex\ServiceProviderInterface;
use Localgod\Carpet\Provider\Jira\Jira;

/**
 * Jira service provider
 *
 * @category Console tool
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link https://github.com/localgod Carpet
 */
class JiraServiceProvider implements ServiceProviderInterface
{

    /**
     * Register the provider
     *
     * @param Application $app
     * @return \Carpet\Provider\Jira
     */
    public function register(Application $app)
    {
        /**
         * Default options
         */
        $options = array(
            'protocol' => 'https://',
            'host' => 'example.com',
            'port' => 8080,
            'path' => '/rest/api/2',
            'user' => 'username',
            'pass' => 'password'
        );

        $app['jira'] = $app->share(function () use ($app, $options) {
            $connOpts = isset($app['jira.connection']) ? array_merge($options, $app['jira.connection']) : $options;

            $jira = new Jira(
                $connOpts['protocol'],
                $connOpts['host'],
                $connOpts['port'],
                $connOpts['path'],
                $connOpts['user'],
                $connOpts['pass']
            );
            return $jira;
        });
    }

    /**
     * Boot the provider
     *
     * @param Application $app
     */
    public function boot(Application $app)
    {
    }
}
