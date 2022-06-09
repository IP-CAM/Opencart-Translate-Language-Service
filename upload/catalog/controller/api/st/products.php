<?php

class ControllerApiStProducts extends Controller
{
    private $logs;

    private string $langID;

    private ModelExtensionModuleStProducts $stProductsModel;

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->logs = new Log('error.log');

        $this->load->model('api/response');
        $this->load->model('extension/module/st/products');

        $this->stProductsModel = new ModelExtensionModuleStProducts($registry);
    }

    public function index()
    {
        if (!$this->_validate())
            ModelApiResponse::badRequest($this->response);

        $this->_populateParams();

        $missingTranslations = $this->stProductsModel->missing($this->langID);

        ModelApiResponse::success($this->response, $missingTranslations);
    }

    public function total()
    {
        $totalProductsCount = $this->stProductsModel->total();

        ModelApiResponse::success($this->response, $totalProductsCount);
    }

    public function without_description()
    {
        $productsCount = $this->stProductsModel->noDescription();

        ModelApiResponse::success($this->response, $productsCount);
    }

    private function _validate(): bool
    {
        if ($this->request->get['lang'] === null)
            return false;

        return true;
    }

    /**
     * Store query params to class variables
     * 
     * @return void 
     */
    private function _populateParams(): void
    {
        $this->langID = $this->request->get['lang'];
    }
}
