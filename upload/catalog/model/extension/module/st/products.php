<?php

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;
use Catalog\Model\Extension\Module\St\ServiceTranslateInterface;

class ModelExtensionModuleStProducts extends Model
{

    public function __construct($registry)
    {
        parent::__construct($registry);
    }

    public function missing($langID): int
    {
        $query = $this->db->query("SELECT COUNT(p.product_id) as translations_missing FROM " . DB_PREFIX . "product p 
            LEFT JOIN (SELECT * FROM " . DB_PREFIX . "product_description WHERE language_id = " . $langID . " ) pd ON p.product_id = pd.product_id
            WHERE pd.product_id IS NULL");

        return (int)$query->row['translations_missing'];
    }

    public function total(): int
    {
        $query = $this->db->query("SELECT COUNT(*) as total_products FROM " . DB_PREFIX . "product");

        return (int)$query->row['total_products'];
    }

    public function noDescription(): int
    {
        $query = $this->db->query("SELECT COUNT(p.product_id) as prod_count FROM " . DB_PREFIX . "product p
            LEFT JOIN " . DB_PREFIX . "product_description pd ON p.product_id = pd.product_id
            WHERE pd.product_id IS NULL ");

        return (int)$query->row['prod_count'];
    }
}
