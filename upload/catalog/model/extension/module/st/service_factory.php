<?php

class ModelExtensionModuleStServiceFactory extends Model
{

    protected $registry;

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->registry = $registry;
    }

    public function select($service)
    {
        if ($service == 'itranslate') {
            $this->load->model('extension/module/st/itranslate');
            return new ModelExtensionModuleStItranslate($this->registry);
        }
    }
}
