<?php

class ControllerApiStHandleHtml extends Controller
{
    protected $registry;

    private $logs;

    private $from;
    private $limit;
    private $htmlCodes = [];

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->model('catalog/product_description');
        $this->load->model('catalog/product_descriptions');
        $this->load->model('api/response');
        $this->load->model('extension/module/st/html_decode');

        $this->logs = new Log('error.log');
    }

    /**
     * This class 1. decode html character to text
     * Example: &#116;&#101;&#115;&#116; -> test
     * 
     * 2.(optional) remove html tags gitven to html_codes param from product's description text
     * Example: <p>test</p> -> test
     * 
     * @url GET /index.php?route=api/st/handle_html
     * 
     * @param string | from 
     * @param string | limit
     * @param serialized json string | html_codes (optional) example: html_codes=["<p>","</p>","<span>","</span>","<strong>","</strong>"]
     * JSON.stringify(['b','div','em','i','p','span','strong'])
     * 
     * @return never 
     * @prints JSON response
     */
    public function index()
    {
        try {
            $this->_populateParams();
            $productsDescriptions = $this->model_catalog_product_descriptions->get($this->from, $this->limit);
        } catch (Exception $ex) {
            $this->logs->write('File: ' . $ex->getFile() . ' | Line: ' . $ex->getLine() . ' | Message: ' . $ex->getMessage());
            ModelApiResponse::badRequest($this->response);
        }

        $this->_decode($productsDescriptions);

        $data = [
            'message' => 'success',
            'products' => count($productsDescriptions)
        ];
        $this->logs->write('from : ' . $this->from . ' | limit: ' . $this->limit);
        $this->logs->write('Products : ' . count($productsDescriptions));
        ModelApiResponse::success($this->response, $data);
    }

    private function _decode($productsDescriptions): void
    {
        foreach ($productsDescriptions as $product) {

            $productDesc = new ModelCatalogProductDescription($this->registry, (int)$product['product_id'], (int)$product['language_id']);
            $productDesc->populate();

            $toDecode = $productDesc->getDescription();

            $htmlDecoder = new ModelExtensionModuleStHtmlDecode($this->registry, $toDecode);

            if (!empty($this->htmlCodes))
                $htmlDecoder->removeHTMLCodes($this->htmlCodes);

            $htmlDecoder->decode();

            $decoded = $htmlDecoder->get();

            $productDesc->setDescription($decoded);
            $productDesc->update();
        }
    }

    private function _populateParams(): void
    {
        if (
            $this->request->get['from'] === null
            || $this->request->get['limit'] === null
        )
            throw new Exception('Parameters missing');

        if ($this->request->get['html_codes'] !== null)
            $this->htmlCodes = json_decode(html_entity_decode($this->request->get['html_codes']), true);

        $this->from = (int)$this->request->get['from'];
        $this->limit = (int)$this->request->get['limit'];
    }
}
