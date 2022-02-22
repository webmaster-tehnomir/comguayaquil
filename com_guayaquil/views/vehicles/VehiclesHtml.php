<?php

namespace guayaquil\views\vehicles;

use guayaquil\Config;
use guayaquil\guayaquillib\data\GuayaquilRequestOEM;
use guayaquil\guayaquillib\data\Language;
use guayaquil\modules\pathway\Pathway;
use guayaquil\View;
use guayaquil\guayaquillib\objects\VehicleListObject;


class VehiclesHtml extends View
{

    public function Display($tpl = 'vehicles', $view = 'view')
    {
        $vin         = $this->input->getString('vin', '');
        $frameNo     = $this->input->getString('frameNo', '');
        $oem         = $this->input->getString('oem', false);
        $operation   = $this->input->getString('operation', '');
        $catalogCode = $this->input->getString('c');
        $ssd         = $this->input->getString('ssd', '');
        $request     = new \stdClass();
        $params      = ['c' => $catalogCode, 'ssd' => $ssd, ''];

        $language = new Language();

        $findType     = $this->input->getString('ft');
        $typeValue    = '';
        $notFoundData = [];
        $ident        = '';
        $requests     = [];

        switch ($findType) {
            case 'findByVIN':
                $type      = [
                    'name'  => 'VIN',
                    'value' => $vin
                ];
                $typeValue = $vin;

                $requests['appendFindVehicleByVIN'] = [
                    'vin' => $vin
                ];

                break;
            case 'findByFrame':
                $type = [
                    'name'  => 'Frame',
                    'value' => $frameNo
                ];

                $typeValue = $frameNo;

                $requests['appendFindVehicleByFrameNo'] = [
                    'frameNo' => $frameNo
                ];

                break;
            case 'execCustomOperation':
                $notFoundData = $this->input->get('data');
                $msg          = implode('-', $notFoundData);
                $type         = [
                    'name'  => $language->t($operation),
                    'value' => $msg
                ];

                $typeValue = $msg;

                $requests['appendExecCustomOperation'] = [
                    'operation' => $operation,
                    'data'      => $this->input->get('data')
                ];

                break;
            case 'findByWizard2':
                $type = [
                    'name'  => $language->t('by' . $findType),
                    'value' => ''
                ];

                $requests['appendFindVehicleByWizard2'] = [
                    'ssd' => $ssd,
                ];

                break;
            case 'FindVehicle':
                $ident = $this->input->getString('identString', '');

                $requests['appendFindVehicle'] = [
                    'ident' => $ident,
                ];

                $type = [
                    'name'  => $language->t('by' . strtolower($findType)),
                    'value' => $ident
                ];

                $typeValue = $ident;
                break;

            case 'findByOEM':
                $type = [
                    'name'  => $language->t('by' . strtolower($findType)),
                    'value' => $oem
                ];

                $requests['appendFindApplicableVehicles'] = [
                    'oem' => $oem,
                ];

                break;

            default:
                $request->error = 'err';
                $type           = ['name' => $findType];
                break;
        }

        if ($catalogCode) {
            $requests['appendGetCatalogInfo'] = [
                'c' => $catalogCode
            ];
        }

        $language = new Language();

        $data = $this->getData($requests, $params);

        if ($data) {
            $vehicles = [];
            if (isset($data[0]) && $data[0] instanceof VehicleListObject) {
                if (!Config::$groupVehicles) {
                    /**
                     * @var VehicleListObject $vehicles
                     */
                    $vehicles = $data[0]->groupColumnsByVehicles();
                } else {
                    $vehicles = $data[0]->groupVehiclesByName();
                }
            }

            $catalogInfo = $catalogCode && isset($data[1]) ? $data[1] : false;

            $pathway = new Pathway();

            if ($catalogInfo) {
                $pathway->addItem($catalogInfo->name, $catalogInfo->link);
            }

            $pathway->addItem($language->t('vehiclesFind'));
            if (isset($typeValue) && !empty($typeValue)) {
                $pathway->addItem($typeValue);
            }


            $this->vin                  = $vin;
            $this->frameNo              = $frameNo;
            $this->type                 = $type;
            $this->pathway              = $pathway->getPathway();
            $this->headers              = !empty($vehicles) ? $vehicles->tableHeaders : [];
            $this->maxField             = Config::$vehiclesMaxField;
            $this->cataloginfo          = $catalogInfo;
            $this->useApplicability     = $catalogInfo ? $catalogInfo->supportdetailapplicability : 0;
            $this->vehicles             = $vehicles ? $vehicles->vehicles : [];
            $this->groupedVehicles      = $vehicles ? $vehicles->groupedByName : false;
            $this->brandName            = $catalogInfo ? $catalogInfo->name : '';
            $this->searchBy             = $findType;
            $this->rest                 = $this->input->getString('r', '');
            $this->vin                  = $vin;
            $this->frameNo              = $frameNo;
            $this->supportQuickGroups   = $catalogInfo && $catalogInfo->supportquickgroups ?: false;
            $this->columns              = Config::$VehiclesColumns;
            $this->oem                  = $this->input->getString('oem');
            $this->customOperationValue = $notFoundData;
            $this->ident                = $ident;
            $this->groupVehicles        = Config::$groupVehicles;
            $this->vinExample           = isset($catalogInfo->vinexample) ? $catalogInfo->vinexample : Config::$defaultVin;
            $this->frameExample         = isset($catalogInfo->frameexample) ? $catalogInfo->frameexample : Config::$defaultFrame;
            $this->showApplicability    = Config::$showApplicability;
        }

        parent::Display($tpl, $view);
    }
}
