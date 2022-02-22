<?php
/**
 * Created by Laximo.
 * User: Elnikov.A.
 * Date: 07.10.17
 * Time: 9:36
 */

namespace guayaquil\views\aftermarket;

use guayaquil\Config;
use guayaquil\guayaquillib\data\GuayaquilRequestAM;
use guayaquil\guayaquillib\data\Language;
use guayaquil\modules\pathway\Pathway;
use guayaquil\View;

class AftermarketHtml extends View
{
    public function Display($tpl = 'aftermarket', $view = 'view')
    {
        $view = isset($_GET['view']) ? $_GET['view'] : 'view';
        $language = new Language();
        if (Config::$useEnvParams) {
            $this->redirect($language->createUrl('catalogs'));
        }

        switch ($view) {
            case 'view':
                $this->displayAftermarket();
                parent::Display($tpl, $view);

                break;
            case 'manufacturerinfo':
                $this->displayManufacturerInfo();
                break;
            case 'findOem':
                $this->displayFindOem();
                break;
        }
    }

    public function displayAftermarket()
    {
        $this->getData();
        $oem = isset($_GET['oem']) ? htmlspecialchars(trim($_GET['oem'])) : '';
        $brand = isset($_GET['brand']) ? htmlspecialchars(trim($_GET['brand'])) : '';

        $options = @$_GET['options'];
        $replacementtypes = @$_GET['replacementtypes'];

        if (!$options) {
            $options = ['crosses'];
        }
        if (!$replacementtypes) {
            $replacementtypes = ['Default'];
        }

        $pathway = new Pathway();

        $pathway->addItem('AfterMarket', '');

        $this->pathway = $pathway->getPathway();

        $this->oem = $oem;
        $this->brand = $brand;
        $this->options = $options;
        $this->replacementtypes = $replacementtypes;
    }

    public function displayFindOem()
    {

        $brand = isset($_GET['brand']) ? $_GET['brand'] : null;
        $oem = isset($_GET['oem']) ? $_GET['oem'] : '';
        $options = isset($_GET['options']) ? $_GET['options'] : '';
        $detailId = isset($_GET['detail_id']) ? $_GET['detail_id'] : false;

        $replacementtypes = @$_GET['replacementtypes'];
        if ($replacementtypes) {
            $replacementtypes = implode(',', $replacementtypes);
        } else {
            $replacementtypes = 'Default';
        }

        $request = new GuayaquilRequestAM('ru_RU');
        if (Config::$useLoginAuthorizationMethod) {
            $request->setUserAuthorizationMethod(Config::$defaultAMUserLogin, Config::$defaultAMUserKey);
        }

        if ($detailId) {
            $request->appendFindDetail($detailId, $options);
            $data = $request->query();
            $data = simplexml_load_string($data);
            $data = $data[0]->FindDetails->detail;

            if (!$data[0] || $request->error) {
                $this->loadTwig('error/tmpl', 'default.twig',
                    ['message' => $request->error, 'more' => $request->errorTrace]);
                $this->loadTwig('aftermarket/tmpl', 'view.twig', []);
            } else {
                if ($data) {

                    $this->loadTwig('aftermarket/tmpl', 'findOem.twig', [
                        'details' => $data
                    ]);
                }
            }
        } else {
            if ($options) {
                $options = implode(',', $options);
            } else {
                $options = '';
            }

            $request->appendFindOEM($oem, $options, $brand, $replacementtypes);

            $data = $request->query();

            if ($request->error != '') {
                echo $request->error;
            } else {
                $data = simplexml_load_string($data);
                $data = $data[0]->FindOEM->detail;
                if (!$data || (!(string)$data['manufacturerid'])) {
                    $request = new GuayaquilRequestAM('ru_RU');
                    if (Config::$useLoginAuthorizationMethod) {
                        $request->setUserAuthorizationMethod(Config::$defaultAMUserLogin, Config::$defaultAMUserKey);
                    }
                    $request->appendFindOEMCorrection($oem);
                    $data = $request->query();
                    $data = simplexml_load_string($data);
                    if (isset($data[0]->FindOEMCorrection->detail)) {
                        $data = $data[0]->FindOEMCorrection->detail;
                    }
                }

                if ($data) {

                    $this->loadTwig('aftermarket/tmpl', 'findOem.twig', [
                        'details' => $data
                    ]);
                }
            }
        }
    }

    public function displayManufacturerInfo()
    {

        $manufacturerid = $_GET['manufacturerid'];

        $request = new GuayaquilRequestAM('en_US');
        if (Config::$useLoginAuthorizationMethod) {
            $request->setUserAuthorizationMethod(Config::$defaultAMUserLogin, Config::$defaultAMUserKey);
        }
        $request->appendManufacturerInfo($manufacturerid);
        $data = $request->query();

        if ($request->error != '') {
            echo $request->error;
        } else {
            $data = simplexml_load_string($data);
            $data = $data[0]->ManufacturerInfo->row;
        }

        $this->loadTwig('aftermarket/tmpl', 'manufacturerInfo.twig', [
            'manufacturerInfo' => $data
        ]);
    }
}