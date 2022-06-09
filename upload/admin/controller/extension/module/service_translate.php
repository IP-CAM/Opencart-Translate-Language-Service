<?php

class ControllerExtensionModuleServiceTranslate extends Controller
{

    private $error = [];

    private $viewData = [];

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->language('extension/module/service_translate');

        $this->load->model('setting/setting');

        $this->_breadcrumbs();

        $this->_populateBasicViewData();
    }

    public function index()
    {

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $this->model_setting_setting->editSetting('st', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }

        $this->viewData['st_service'] = $this->_get('st_service');
        $this->viewData['st_meta_suffix'] = $this->_get('st_meta_suffix');
        $this->viewData['st_meta_from_title'] = $this->_get('st_meta_from_title');
        $this->viewData['st_translate_description'] = $this->_get('st_translate_description');

        $this->viewData['st_itranslate_api_key'] = $this->_get('st_itranslate_api_key');
        $this->viewData['st_itranslate_api_version'] = $this->_get('st_itranslate_api_version');
        $this->viewData['st_itranslate_lang'] = $this->_get('st_itranslate_lang');


        $this->document->setTitle($this->language->get('heading_title_settings'));

        $this->load->model('localisation/language');
        $this->viewData['languages'] = $this->model_localisation_language->getLanguages();

        $this->response->setOutput($this->load->view('extension/module/service_translate_settings', $this->viewData));
    }

    public function dashboard()
    {
        $this->document->setTitle($this->language->get('heading_title'));

        $this->viewData['st_service'] = $this->_get('st_service');
        $this->viewData['st_meta_suffix'] = $this->_get('st_meta_suffix');
        $this->viewData['st_meta_from_title'] = $this->_get('st_meta_from_title');
        $this->viewData['st_translate_description'] = $this->_get('st_translate_description');

        $this->response->setOutput($this->load->view('extension/module/service_translate_dashboard', $this->viewData));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/er_es/settings')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        return !$this->error;
    }

    /**
     * Search value for given property first on request then on stored setting.
     * If it fail returns empty string
     * 
     * @param string $property 
     * @return string|array 
     */
    private function _get($property): string | array
    {
        if (isset($this->request->post[$property]))
            return $this->request->post[$property];

        if (null !== $this->config->get($property))
            return $this->config->get($property);

        return '';
    }


    private function _populateBasicViewData()
    {

        $this->viewData['header'] = $this->load->controller('common/header');
        $this->viewData['column_left'] = $this->load->controller('common/column_left');
        $this->viewData['footer'] = $this->load->controller('common/footer');
    }

    private function _loadJSCSS()
    {
        $this->document->addScript('../system/javascript_libs/vue.js');
        $this->document->addScript('../system/javascript_libs/vue-router.min.js');
        $this->document->addScript('../system/javascript_libs/vuex.js');
    }

    private function _breadcrumbs()
    {
        $this->viewData['breadcrumbs'] = array();

        $this->viewData['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
        );

        $this->viewData['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'user_token=' . $this->session->data['user_token'], 'SSL')
        );

        if (!isset($this->request->get['module_id'])) {
            $this->viewData['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/service_translate', 'user_token=' . $this->session->data['user_token'], 'SSL')
            );
        } else {
            $this->viewData['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/service_translate', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
            );
        }
    }
}
