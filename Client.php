<?php

namespace JsonRPC;

use RuntimeException;
use BadFunctionCallException;
use InvalidArgumentException;

/**
 * JsonRPC client class
 *
 * @package JsonRPC
 * @author Frederic Guillot
 * @license Unlicense http://unlicense.org/
 */
class Client
{
    /**
     * URL of the server
     *
     * @access private
     * @var string
     */
    private $url;

	/**
     * secret token of the server
     *
     * @access private
     * @var string
     */
	private $secret;
	
    /**
     * HTTP client timeout
     *
     * @access private
     * @var integer
     */
    private $timeout;

    /**
     * Username for authentication
     *
     * @access private
     * @var string
     */
    private $username;

    /**
     * Password for authentication
     *
     * @access private
     * @var string
     */
    private $password;

    /**
     * True for a batch request
     *
     * @access public
     * @var boolean
     */
    public $is_batch = false;

    /**
     * Batch payload
     *
     * @access public
     * @var array
     */
    public $batch = array();

    /**
     * Enable debug output to the php error log
     *
     * @access public
     * @var boolean
     */
    public $debug = false;

    /**
     * Default HTTP headers to send to the server
     *
     * @access private
     * @var array
     */
    private $headers = array(
        'Connection: close',
        'Content-Type: application/json',
        'Accept: application/json'
    );

    /**
     * Constructor
     *
     * @access public
     * @param  string    $url         Server URL
     * @param  integer   $timeout     Server URL
     * @param  array     $headers     Custom HTTP headers
     */
    public function __construct($url, $secret, $timeout = 5, $headers = array())
    {
        $this->url = $url;
		$this->secret = $secret;
        $this->timeout = $timeout;
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * Automatic mapping of procedures
     *
     * @access public
     * @param  string   $method   Procedure name
     * @param  array    $params   Procedure arguments
     * @return mixed
     */
    public function __call($method, array $params)
    {
        // Allow to pass an array and use named arguments
        if (count($params) === 1 && is_array($params[0])) {
            $params = $params[0];
        }

        return $this->execute($method, $params);
    }

    /**
     * Set authentication parameters
     *
     * @access public
     * @param  string   $username   Username
     * @param  string   $password   Password
     */
    public function authentication($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Start a batch request
     *
     * @access public
     * @return Client
     */
    public function batch()
    {
        $this->is_batch = true;
        $this->batch = array();

        return $this;
    }

    /**
     * Send a batch request
     *
     * @access public
     * @return array
     */
    public function send()
    {
        $this->is_batch = false;

        return $this->parseResponse(
            $this->doRequest($this->batch)
        );
    }

    /**
     * Execute a procedure
     *
     * @access public
     * @param  string   $procedure   Procedure name
     * @param  array    $params      Procedure arguments
     * @return mixed
     */
    public function execute($procedure, array $params = array())
    {
        if ($this->is_batch) {
            $this->batch[] = $this->prepareRequest($procedure, $params);
            return $this;
        }

        return $this->parseResponse(
            $this->doRequest($this->prepareRequest($procedure, $params))
        );
    }

    /**
     * Prepare the payload
     *
     * @access public
     * @param  string   $procedure   Procedure name
     * @param  array    $params      Procedure arguments
     * @return array
     */
    public function prepareRequest($procedure, array $params = array())
    {
        $payload = array(
            'jsonrpc' => '2.0',
            'method' => $procedure,
            'id' => mt_rand()
        );
		
        if (! empty($params)) {
            $payload['params'] = $params;
        }

        return $payload;
    }

    /**
     * Parse the response and return the procedure result
     *
     * @access public
     * @param  array     $payload
     * @return mixed
     */
    public function parseResponse(array $payload)
    {
        if ($this->isBatchResponse($payload)) {

            $results = array();

            foreach ($payload as $response) {
                $results[] = $this->getResult($response);
            }

            return $results;
        }

        return $this->getResult($payload);
    }

    /**
     * Return true if we have a batch response
     *
     * @access public
     * @param  array    $payload
     * @return boolean
     */
    private function isBatchResponse(array $payload)
    {
        return array_keys($payload) === range(0, count($payload) - 1);
    }

    /**
     * Get a RPC call result
     *
     * @access public
     * @param  array    $payload
     * @return mixed
     */
    public function getResult(array $payload)
    {
        if (isset($payload['error']['code'])) {
            $this->handleRpcErrors($payload['error']['code']);
        }

        return isset($payload['result']) ? $payload['result'] : null;
    }

    /**
     * Throw an exception according the RPC error
     *
     * @access public
     * @param  integer    $code
     */
    public function handleRpcErrors($code)
    {
        switch ($code) {
            case -32601:
                throw new BadFunctionCallException('Procedure not found');
            case -32602:
                throw new InvalidArgumentException('Invalid arguments');
            default:
                throw new RuntimeException('Invalid request/response');
        }
    }

    /**
     * Do the HTTP request
     *
     * @access public
     * @param  string   $payload   Data to send
     */
    public function doRequest($payload)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_USERAGENT, 'JSON-RPC PHP Client');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        if ($this->username && $this->password) {
            curl_setopt($ch, CURLOPT_USERPWD, $this->username.':'.$this->password);
        }
		
		if ($this->secret) {
			curl_setopt($ch, CURLOPT_USERPWD, ':'.$this->secret);
		}

        $http_body = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($http_code === 401 || $http_code === 403) {
            throw new RuntimeException('Access denied');
        }

        $response = json_decode($http_body, true);

        if ($this->debug) {
            error_log('==> Request: '.PHP_EOL.json_encode($payload, JSON_PRETTY_PRINT));
            error_log('==> Response: '.PHP_EOL.json_encode($response, JSON_PRETTY_PRINT));
        }

        curl_close($ch);

        return is_array($response) ? $response : array();
    }
}
