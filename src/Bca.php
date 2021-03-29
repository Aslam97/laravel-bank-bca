<?php

namespace Aslam\Bca;

use Aslam\Bca\Exceptions\ConnectionException;
use Aslam\Bca\Exceptions\RequestException;
use Aslam\Bca\Modules\OGP;
use Aslam\Bca\Traits;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;

class Bca
{
    use Traits\Token;

    /**
     * API url
     *
     * @var string
     */
    protected $apiUrl;

    /**
     * Application client Id
     *
     * @var string
     */
    private $clientId;

    /**
     * Application client secret
     *
     * @var string
     */
    private $clientSecret;

    /**
     * token
     *
     * @var string
     */
    private $token;

    /**
     * Init
     *
     * @param  mixed $token
     * @return void
     */
    public function __construct($token = null)
    {
        $this->apiUrl = config('bank-bni.api_url');
        $this->clientId = config('bank-bni.client_id');
        $this->clientSecret = config('bank-bni.client_secret');
        $this->token = $token;
    }

    /**
     * sendRequest
     *
     * @param  string $httpMethod
     * @param  string $requestUrl
     * @param  array $options
     * @return \Aslam\Bca\Response
     *
     * @throws \Aslam\Bca\Exceptions\RequestException
     */
    public function sendRequest(string $httpMethod, string $requestUrl, array $data = [])
    {
        try {
            $options = ['http_errors' => false];

            if (!$this->token) {
                $options = array_merge($options, $data);
            } else {
                // set token
                $options['query'] = ['access_token' => $this->token];

                // set headers
                $options['headers'] = [
                    'Content-Type' => 'application/json',
                    'X-API-Key' => config('bank-bni.api_key'),
                ];

                // set body
                $options['json'] = generate_signature($data);
            }

            return tap(
                new Response(
                    (new Client())->request($httpMethod, $requestUrl, $options)
                ),
                function ($response) {
                    if (!$response->successful()) {
                        $response->throw();
                    }
                }
            );

        } catch (ConnectException $e) {
            throw new ConnectionException($e->getMessage(), 0, $e);
        } catch (RequestException $e) {
            return $e->response;
        }
    }

    /**
     * setToken
     *
     * @param  string $token
     * @return $this
     */
    public function setToken(string $token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * One Gate Payment
     *
     * @return \Aslam\Bca\Modules\OGP
     */
    public function oneGatePayment()
    {
        return new OGP($this->token);
    }
}
