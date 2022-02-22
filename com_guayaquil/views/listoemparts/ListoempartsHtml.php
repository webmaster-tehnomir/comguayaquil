<?php
/**
 * Created by Laximo
 * User: elnikov.a
 * Date: 27.03.18
 * Time: 9:52
 */

namespace guayaquil\views\listoemparts;

use guayaquil\View;


/**
 * @property int total
 */
class ListoempartsHtml extends View
{
    public function Display($tpl = 'listoemparts', $view = 'view')
    {
        $catalog = $this->input->getString('c', '');
        $ssd     = $this->input->getString('ssd', '');
        $vid     = $this->input->getString('vid', '');

        $params      = ['c' => $catalog, 'ssd' => $ssd, ''];

        $requests = [
            'appendListOemParts' => [
                'vid' => $vid,
            ]
        ];

        $data = $this->getData($requests, $params);

        if ($data) {
            $this->data  = $data[0]->oemParts;
            $this->total = count($data[0]->oemParts);
        }

        parent::Display($tpl, $view);
    }
}