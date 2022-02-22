<?php

namespace guayaquil\views\catalogs;

use guayaquil\Config;
use guayaquil\View;

/**
 * @property string      frameExample
 * @property string      vinExample
 * @property array       catalogs
 * @property int         columns
 * @property float       elemInRow
 * @property int         elemCount
 * @property int         rest
 * @property string      vinFrameExample
 * @property bool        letters
 * @property bool|string rev
 * @property bool|string revDesc
 */
class CatalogsHtml extends View
{

    public function Display($tpl = 'catalogs', $view = 'view')
    {
        $requests = [
            'appendListCatalogs' => []
        ];

        $data = $this->getData($requests);
        if ($data) {
            $dataObj  = $data[0]->catalogs;
            $examples = $data[0]->examples;

            $columns   = property_exists(new Config(), 'catalogColumns') ? Config::$catalogColumns : 3;
            $elemCount = count($dataObj ?: []);
            $elemInRow = floor(($elemCount) / $columns);
            $rest      = $elemCount % $columns;

            $this->frameExample    = $examples[1];
            $this->vinExample      = $examples[0];
            $this->catalogs        = $dataObj;
            $this->columns         = $columns;
            $this->elemInRow       = $elemInRow;
            $this->elemCount       = $elemCount;
            $this->rest            = $rest;
            $this->vinFrameExample = $examples[rand(0, 1)];
            $this->letters         = Config::$showCatalogsLetters;
        }

        parent::Display($tpl, $view);
    }
}




