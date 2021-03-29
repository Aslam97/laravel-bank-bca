<?php

namespace Aslam\Bca;

use Aslam\Bca\Exceptions\ConnectionException;
use Aslam\Bca\Exceptions\RequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;

class Bca
{
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
    protected $clientId;

    /**
     * Application client secret
     *
     * @var string
     */
    protected $clientSecret;

    /**
     * apiKey
     *
     * @var mixed
     */
    private $apiKey;

    /**
     * apiSecret
     *
     * @var mixed
     */
    private $apiSecret;

    /**
     * corporateId
     *
     * @var string
     */
    protected $corporateID;

    /**
     * token
     *
     * @var string
     */
    private $token;

    /**
     * servicePath
     *
     * @var string
     */
    private $servicePath = 'Aslam\\Bca\\Services\\';

    /**
     * Init
     *
     * @param  mixed $token
     * @return void
     */
    public function __construct($token = null)
    {
        $this->apiUrl = config('bank-bca.api_url');
        $this->clientId = config('bank-bca.client_id');
        $this->clientSecret = config('bank-bca.client_secret');
        $this->apiKey = config('bank-bca.api_key');
        $this->apiSecret = config('bank-bca.api_secret');
        $this->corporateID = config('bank-bca.corporate_id');
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
    public function sendRequest(string $httpMethod, string $relativeUrl, array $requestBody = [])
    {
        try {
            $options = ['http_errors' => false];

            if (!$this->token) {
                $options = array_merge($options, $requestBody);

            } else {

                $url = url_sort_lexicographically("{$httpMethod}:{$relativeUrl}");
                $timestamp = bca_timestamp();
                ksort($requestBody);

                // set headers
                $options['headers'] = [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token,
                    'Content-Type' => 'application/json',
                    'X-BCA-Key' => $this->apiKey,
                    'X-BCA-Timestamp' => $timestamp,
                    'X-BCA-Signature' => bca_signature($url, $this->token, $this->apiSecret, $timestamp, $requestBody),
                ];

                $methods = ['POST', 'PUT', 'PATCH'];

                if (in_array($httpMethod, $methods)) {
                    $options['body'] = json_encode($requestBody, JSON_UNESCAPED_SLASHES);
                }
            }

            return tap(
                new Response(
                    (new Client())->request($httpMethod, $this->apiUrl . $relativeUrl, $options)
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
     * service
     *
     * @param  string $serviceName
     * @return \Aslam\Bca\Modules
     */
    public function service($serviceName)
    {
        $service = $this->servicePath . $serviceName;
        return new $service($this->token);
    }
}
