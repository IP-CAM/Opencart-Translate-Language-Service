<?php

class ControllerApiStTranslate extends Controller
{
    private $logs;

    private int $sourceLangID;
    private int $translateLangID;

    private string $translationService;
    private $translationServiceModel;
    private ModelExtensionModuleStServiceFactory $translationServiceFactory;

    private int $from;
    private int $limit;

    private bool $translateDescription = false;
    private bool $translateMetaTitle = true;
    private bool $metaFromTitle = true;

    private string $metaTitleSuffix = '';

    private array $completeTranslations = [];

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->logs = new Log('error.log');

        $this->load->model('api/response');
        $this->load->model('extension/module/st/service_factory');
        $this->load->model('catalog/product_description');
        $this->load->model('catalog/product_descriptions');

        $this->translationServiceFactory = new ModelExtensionModuleStServiceFactory($this->registry);

        $this->completeTranslations = [
            'name'          => '',
            'description'   => '',
            'metaTitle'     => ''
        ];
    }

    /**
     * This class translate opencart products name, description, meta title using translation service
     * 
     * @url GET /index.php?route=api/st/translate
     * 
     * call example:
     * http://opencart.test:8100/index.php?route=api/st/translate&from=0&limit=2&translation_service=itranslate&source_lang=2&translate_lang=3&meta_title_suffix=Lavand | &meta_from_title=true&translate_description=true
     * 
     * @param string | translation_service 
     * @param int | source_lang
     * @param int | translate_lang 
     * @param int | limit
     * @param int | from 
     * @param bool | translate_description 
     * @param bool | translate_meta_title
     * @param bool | meta_from_title 
     * @param string | meta_title_suffix
     * 
     * @return never 
     * @prints JSON response
     */
    public function index()
    {
        if (!$this->_validate())
            ModelApiResponse::badRequest($this->response);

        $this->_populateParams();

        $this->translationServiceModel = $this->_createTranslationModel();

        $productsDescriptions = $this->model_catalog_product_descriptions->getByLang($this->sourceLangID, $this->from, $this->limit);

        $totalTranslatedProducts = 0;
        foreach ($productsDescriptions as $product) {

            $productdescription = $this->_createSourceModel((int)$product['product_id']);

            $this->completeTranslations['name'] = $this->_getTranslation($productdescription->getName());

            if ($this->translateDescription)
                $this->completeTranslations['description'] = $this->_getTranslation($productdescription->getDescription());

            if ($this->translateMetaTitle)
                $this->completeTranslations['metaTitle'] = $this->_handleMetaTranslate($productdescription);

            $translation = $this->_createTranslatedModel((int)$product['product_id'], $this->translateLangID);
            if ($this->_store($translation))
                $totalTranslatedProducts++;

            usleep(250000);
        }

        $data = [
            'message' => 'success',
            'products_description_updated' => $totalTranslatedProducts
        ];

        ModelApiResponse::success($this->response, $data);
    }

    private function _validate(): bool
    {
        if (
            $this->request->get['translation_service'] === null
            || $this->request->get['source_lang'] === null
            || $this->request->get['translate_lang'] === null
            || $this->request->get['from'] === null
            || $this->request->get['limit'] === null
        )
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
        $this->_parseRequiredParams();
        $this->_parseOptionalParams();
    }

    private function _parseRequiredParams(): void
    {
        $this->translationService = $this->request->get['translation_service'];
        $this->sourceLangID = (int)$this->request->get['source_lang'];
        $this->translateLangID = (int)$this->request->get['translate_lang'];
        $this->from = (int)$this->request->get['from'];
        $this->limit = (int)$this->request->get['limit'];
    }

    private function _parseOptionalParams(): void
    {
        if (isset($this->request->get['translate_description']))
            $this->translateDescription = filter_var($this->request->get['translate_description'], FILTER_VALIDATE_BOOLEAN);

        if (isset($this->request->get['translate_meta_title']))
            $this->translateMetaTitle = filter_var($this->request->get['translate_meta_title'], FILTER_VALIDATE_BOOLEAN);

        if (isset($this->request->get['meta_from_title']))
            $this->metaFromTitle = filter_var($this->request->get['meta_from_title'], FILTER_VALIDATE_BOOLEAN);

        if (isset($this->request->get['meta_title_suffix']))
            $this->metaTitleSuffix = $this->request->get['meta_title_suffix'];
    }

    /**
     * Using a factory it creates the translation service model selected by user
     * 
     * @TODO Only itranslate service is available. Implement other services
     * 
     * @return mixed (ModelExtensionModuleStItranslate)
     */
    private function _createTranslationModel(): mixed
    {
        $translationService = $this->translationServiceFactory->select($this->translationService);
        $translationService->setLanguages($this->sourceLangID, $this->translateLangID);

        return $translationService;
    }

    private function _createSourceModel($productID): ModelCatalogProductDescription
    {
        $sourceModel = new ModelCatalogProductDescription($this->registry, $productID, $this->sourceLangID);
        $sourceModel->populate();

        return $sourceModel;
    }

    /**
     * Translate text from the selected service
     * @param string $text 
     * @return string 
     */
    private function _getTranslation($text): string
    {
        if ($text == '')
            return '';

        try {
            return $this->translationServiceModel->tranlate($text);
        } catch (Exception $ex) {
            $this->logs->write('File: ' . $ex->getFile() . ' | Line: ' . $ex->getLine() . ' | Message: ' . $ex->getMessage());
            return '';
        }
    }

    private function _handleMetaTranslate($productdescription): string
    {
        if ($this->metaFromTitle)
            return $this->metaTitleSuffix . $this->completeTranslations['name'];

        $metaTitle = $productdescription->getMetaTitle();

        return $this->metaTitleSuffix . $this->_getTranslation($metaTitle);
    }

    private function _createTranslatedModel($productID, $toLang): ModelCatalogProductDescription
    {
        $translationModel = new ModelCatalogProductDescription($this->registry, $productID, $toLang);
        $translationModel->setName($this->completeTranslations['name']);
        $translationModel->setDescription($this->completeTranslations['description']);
        $translationModel->setMetaTitle($this->completeTranslations['metaTitle']);

        return $translationModel;
    }

    private function _store($translation): bool
    {
        if ($translation->exists())
            return $translation->update();
        else
            return $translation->insert();
    }
}
