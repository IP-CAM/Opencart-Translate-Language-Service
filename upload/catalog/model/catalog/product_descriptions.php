<?php

class ModelCatalogProductDescriptions extends Model
{

    public function get($start = 0, $limit = 10): array
    {
        $sql = "SELECT product_id, language_id FROM " . DB_PREFIX . "product_description ";

        $sql .= " LIMIT " . (int)$start . "," . (int)$limit;

        $query = $this->db->query($sql);

        if (empty($query->rows))
            throw new Exception('No product description found');

        return $query->rows;
    }

    public function getByLang($lang, $start = 0, $limit = 10): array
    {
        $sql = "SELECT product_id FROM " . DB_PREFIX . "product_description "
            . " WHERE language_id = " . $lang;

        $sql .= " LIMIT " . (int)$start . "," . (int)$limit;

        $query = $this->db->query($sql);

        if (empty($query->rows))
            throw new Exception('No product description found');

        return $query->rows;
    }

    public function count()
    {
        $sql = "SELECT count(*) as all FROM " . DB_PREFIX . "product_description ";

        $query = $this->db->query($sql);

        return $query->rows;
    }
}
