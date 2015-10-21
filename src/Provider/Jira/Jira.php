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

use GuzzleHttp\Client;

/**
 * Jira broker
 *
 * @category Console tool
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link https://github.com/localgod Carpet
 */
class Jira
{

    /**
     * HTTP client
     *
     * @var GuzzleHttp\Client
     */
    private $client;

    /**
     * Protocol to use
     *
     * @var string
     */
    private $protocol;

    /**
     * Host to search
     *
     * @var string
     */
    private $host;

    /**
     * Port to use
     *
     * @var integer
     */
    private $port;

    /**
     * Base path to resource
     *
     * @var string
     */
    private $path;

    /**
     * User to authenticate as
     *
     * @var string
     */
    private $user;

    /**
     * Password to authenticate with
     *
     * @var string
     */
    private $pass;

    /**
     * Construct new jira access point
     *
     * @param string $protocol
     *            Protocol to use
     * @param string $host
     *            Host name
     * @param integer $port
     *            Host name
     * @param integer $path
     *            base path to resource
     * @param string $user
     *            User name
     * @param string $pass
     *            Password
     *
     * @return void
     */
    public function __construct($protocol, $host, $port, $path, $user, $pass)
    {
        $this->protocol = $protocol;
        $this->host = $host;
        $this->port = $port;
        $this->path = $path;
        
        $this->client = new Client([
            'base_uri' => $this->protocol . $this->host . ':' . $this->port . $this->path . '/'
        ]);
        $this->options = [
            'auth' => [
                $user,
                $pass
            ],
            'debug' => false
        ];
    }

    /**
     * Get list of all projects
     *
     * @return array
     */
    public function projects()
    {
        $query = 'project';
        return json_decode($this->client->request('GET', $query, $this->options)->getBody(), true);
    }

    /**
     * Get a user group
     *
     * @return array
     */
    public function group($groupName = 'jira-users', $expand = 'users')
    {
        $query = 'group?groupname=' . $groupName . '&expand=' . $expand;
        return json_decode($this->client->request('GET', $query, $this->options)->getBody(), true);
    }

    /**
     * Get a project
     *
     * @param string $key
     *            The project key to search for
     *
     * @return array
     */
    public function project($key)
    {
        $query = 'project/' . $key;
        return json_decode($this->client->request('GET', $query, $this->options)->getBody(), true);
    }
}
