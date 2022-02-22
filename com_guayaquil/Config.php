<?php

namespace guayaquil;

use yii\helpers\ArrayHelper;

if (Config::$dev) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

class Config
{
    const baseDir = __DIR__;
    const imageSize = 250;
    public static $dev = false;
    public static $ui_localization = 'ru';
    public static $catalog_data = 'ru_RU';
    public static $useLoginAuthorizationMethod = true;
    public static $vehiclesMaxField = 20;
    public static $defaultUserLogin = '';
//    public static $defaultUserLogin = 's.chaban@tehnomir.com.ua';
//    public static $defaultUserKey = 'aEbLmOMG7EY3hxKZ';
    public static $defaultUserKey = '';

    public static $defaultAMUserLogin = '';
    public static $defaultAMUserKey = '';
    /* ws.oem web-service url */
    public static $oemServiceUrl = 'ws.laximo.net';

    /* You can use it if you want to authorize by another laximo.oem user. If false, all users are guests.  */
    public static $useWebserviceAuthorize = false;

    /* get login, key, backurl from environment */
    public static $useEnvParams = false;

    /* show start page. Catalogs list will shown if false */
    public static $showWelcomePage = true;

    /* show demo to guest. Might be true if authorization off */
    public static $showToGuest = true;

    /* show request text and response xml-message. Can be used on development stage */
    public static $showRequest = false;

    /* show quick-groups tree to guest */
    public static $showGroupsToGuest = true;

    /* show oem-numbers on unit page, quick-details page and in xml-response message */
    public static $showOemsToGuest = true;

    /* Be carefull. It's not tested yet. Show find by oem field, find all detail usage in modification and details-list in modification */
    public static $showApplicability = true;

    /* Url to page where you can see offers to current detail. Use {article} and {brand} to replace in template */
    public static $SiteDomain = 'http://test.loc/com/index.php?keyword={article}&brand={brand}';

    /* image placeholder */
    public static $imagePlaceholder = 'com_guayaquil/assets/images/no-image.gif';

    /* columns on catalogs list page */
    public static $catalogColumns = 3;

    /* be shown if no example in response data */
    public static $defaultVin = 'KMHVD34N8VU263043';

    /* be shown if no example in response data */
    public static $defaultFrame = 'XZU423-0001026';

    /* added big letters to catalog names, so you can find your catalog easier */
    public static $showCatalogsLetters = true;

    /* Use your custom css from com_guayaquil/assets/css  */
    public static $theme = 'guayaquil';
    public static $backurlError = 'index.php?task=error&type=backurl';
    public static $linkTarget = '_parent';

    public static $VehiclesColumns = [
        'brand',
        'name',
        'date',
        'datefrom',
        'dateto',
        'model',
        'framecolor',
        'trimcolor',
        'modification',
        'grade',
        'frame',
        'engine',
        'engineno',
        'transmission',
        'doors',
        'manufactured',
        'options',
        'creationregion',
        'destinationregion',
        'description'
    ];

    public static $groupVehicles = false;
    public static $toolbarPages = [
        'catalog',
        'catalogs',
        'error',
        'qgroups',
        'vehicle',
        'vehicles',
        'wizard2',
        'qdetails',
        'unit',
        'applicabilitydetails'
    ];
}
Config::$defaultUserLogin = ArrayHelper::getValue(\Yii::$app->params, 'laximo.login');
Config::$defaultUserKey = ArrayHelper::getValue(\Yii::$app->params, 'laximo.password');
Config::$defaultAMUserLogin = ArrayHelper::getValue(\Yii::$app->params, 'laximo.loginAM');
Config::$defaultAMUserKey = ArrayHelper::getValue(\Yii::$app->params, 'laximo.passwordAM');
Config::$SiteDomain = ArrayHelper::getValue(\Yii::$app->params, 'url.fe') . '/index.php?r=product/search&SearchForm[code]={article}';
