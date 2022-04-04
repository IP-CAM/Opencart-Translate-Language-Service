<?php

namespace Catalog\Model\Extension\Module\St;

interface ServiceTranslateInterface
{
    /**
     * Set translation languages. 
     * Find services languages from config for the given OC lang ids
     * 
     * @param int $sourceLangID 
     * @param int $translateLangID 
     * @return void 
     */
    public function setLanguages($sourceLangID, $translateLangID): void;

    /**
     * translates text
     * @return string 
     */
    public function tranlate($text): string;
}
