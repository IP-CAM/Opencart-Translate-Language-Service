<?php

class ModelCatalogProductDescription extends Model
{

    private $productID;
    private $languageID;
    private $name = '';
    private $description = '';
    private $metaTitle = '';
    private $tag = '';
    private $metaDescription = '';
    private $metaKeyword = '';

    public function __construct($registry, $productID, $langID)
    {
        parent::__construct($registry);

        $this->productID = $productID;
        $this->languageID = $langID;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    public function insert(): bool
    {
        return $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$this->productID
            . "', language_id = '" . (int)$this->languageID
            . "', name = '" . $this->db->escape($this->name)
            . "', description = '" . $this->db->escape($this->description)
            . "', tag = ''"
            . ", meta_title = '" . $this->db->escape($this->metaTitle)
            . "', meta_description = ''"
            . ", meta_keyword = ''");
    }

    public function update(): bool
    {
        return $this->db->query("UPDATE " . DB_PREFIX . "product_description SET  name = '" . $this->db->escape($this->name)
            . "', description = '" . $this->db->escape($this->description)
            . "', tag = ''"
            . ", meta_title = '" . $this->db->escape($this->metaTitle)
            . "', meta_description = ''"
            . ", meta_keyword = ''"
            . " WHERE product_id=" . (int)$this->productID
            . " AND language_id=" . (int)$this->languageID);
    }

    public function exists(): bool
    {
        $productOptionValue = $this->_getFromDB();

        if ($productOptionValue->num_rows === 0)
            return false;

        return true;
    }

    public function populate(): void
    {
        $this->_populateValuesFromDB();
    }

    public function get(): stdClass
    {
        if ($this->name == '' || $this->description == '' || $this->metaTitle == '')
            $this->_populateValuesFromDB();

        return $this->_convertClassToSimpleObject();
    }

    private function _populateValuesFromDB()
    {
        $productDescriptionRaw = $this->_getFromDB();

        if ($productDescriptionRaw->num_rows == 0)
            throw new Exception('No Description found');

        $this->name = $productDescriptionRaw->row['name'];
        $this->description = $productDescriptionRaw->row['description'];
        $this->tag = $productDescriptionRaw->row['tag'];
        $this->metaTitle = $productDescriptionRaw->row['meta_title'];
        $this->metaDescription = $productDescriptionRaw->row['meta_description'];
        $this->metaKeyword = $productDescriptionRaw->row['meta_keyword'];
    }

    private function _getFromDB(): stdClass
    {
        return $this->db->query("SELECT * FROM oc_product_description 
			WHERE product_id = " . $this->productID . "
			AND language_id = " . $this->languageID);
    }

    private function _convertClassToSimpleObject(): stdClass
    {
        return (object)[
            'product_id' => (int)$this->productID,
            'language_id' => (int)$this->languageID,
            'name' => $this->productID,
            'description' => $this->optionID,
            'tag' => $this->tag,
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
            'meta_keyword' => $this->metaKeyword
        ];
    }
}
