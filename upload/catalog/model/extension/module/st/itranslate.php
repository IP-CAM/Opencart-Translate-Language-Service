<?php

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;
use Catalog\Model\Extension\Module\St\ServiceTranslateInterface;

class ModelExtensionModuleStItranslate extends Model
{

    private $apiKey = '';
    private $url = '';

    private $str;
    private $fromLang;
    private $toLang;

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->apiKey = $this->config->get('st_itranslate_api_key');
        $this->url = 'https://dev-api.itranslate.com/translation/' . $this->config->get('st_itranslate_api_version') . '/';
    }

    public function setLanguages($sourceLangID, $translateLangID): void
    {
        $this->fromLang = $this->config->get('st_itranslate_lang')[$sourceLangID];
        $this->toLang = $this->config->get('st_itranslate_lang')[$translateLangID];
    }

    public function tranlate($text): string
    {
        $request = $this->_makeRequest($text);

        return $this->_parse($request);
    }

    private function _makeRequest($text): Response
    {
        if ($text == null)
            throw new Exception('No text to translate');

        $client = new Client();

        return  $client->post($this->url, [
            'headers' => [
                'Content-type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey,
            ],
            'json' => [
                'source' => [
                    'dialect' => $this->fromLang,
                    'text' => strip_tags($text)
                ],
                'target' => ['dialect' => $this->toLang]
            ]
        ]);
    }

    private function _parse($request): string
    {
        if ($request->getStatusCode() == 200)
            return json_decode($request->getBody())->target->text;

        throw new Exception('Invalid Response');
    }
}
