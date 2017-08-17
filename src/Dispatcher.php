<?php

/**
 * Created by PhpStorm.
 * User: silver
 * Date: 12/08/17
 * Time: 11:40
 */

namespace Silver;

/**
 * Class Dispatcher
 * @package Silver
 */
class Dispatcher
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array|bool
     */
    protected $params;

    /**
     * Dispatcher constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->params = parse_ini_file("conf/param.ini");
        $this->httpClient = new \GuzzleHttp\Client();
        $this->url = $url.$this->params['api'];
    }

    /**
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function send(array $data) : array
    {
        $store = array_shift($data);

        $response = $this->httpClient->post($this->url, [
            'headers' => [
                'store' => $store,
		        'Content-Type' => 'application/json',
                'token' => $this->params['token']
            ],
            'body' => json_encode($data, JSON_UNESCAPED_UNICODE)
        ]);

        if($response->getStatusCode() >= 400) {
            throw new \Exception($response->getBody());
        }

        $json = \GuzzleHttp\json_decode($response->getBody()->getContents());

        return [
            'id' => $json->id,
            'slug' => $json->url_key
        ];
    }
}
