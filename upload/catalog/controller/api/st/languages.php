<?php

class ControllerApiStLanguages extends Controller
{

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->logs = new Log('error.log');

        $this->load->model('api/response');
        $this->load->model('localisation/language');
    }

    public function index()
    {
        $languages = $this->model_localisation_language->getLanguages();

        ModelApiResponse::success($this->response, $languages);
    }

    public function source_for_lang()
    {

        if (!$this->_validateSource())
            ModelApiResponse::badRequest($this->response);

        $toTranslate = (int)$this->request->get['lang_id'];

        $default = (int)$this->config->get('config_language_id');

        if ($toTranslate !== $default)
            ModelApiResponse::success($this->response, $default);

        ModelApiResponse::badRequest($this->response);
    }

    private function _validateSource(): bool
    {
        if ($this->request->get['lang_id'] === null)
            return false;

        return true;
    }
}
