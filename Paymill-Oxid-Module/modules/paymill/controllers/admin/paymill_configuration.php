<?php

class paymill_configuration extends Shop_Config
{

    const PAYMILL_MODULE_NAME = 'paymill';

    protected $_sThisTemplate = 'paymill_configuration.tpl';

    public function render()
    {

        $myConfig = $this->getConfig();
        $aDbVariables = $this->_loadConfVars($myConfig->getShopId(), $moduleName = '');
        $aConfVars = $aDbVariables['vars'];

        foreach ($this->_aConfParams as $sType => $sParam) {
            $this->_aViewData[$sParam] = $aConfVars[$sType];
        }

        return $this->_sThisTemplate;
    }

    public function saveConfVars()
    {
        $myConfig = $this->getConfig();

        $aConfBools = oxConfig::getParameter("confbools");
        $aConfStrs = oxConfig::getParameter("confstrs");
        $aConfArrs = oxConfig::getParameter("confarrs");
        $aConfAarrs = oxConfig::getParameter("confaarrs");

        if (is_array($aConfStrs)) {
            foreach ($aConfStrs as $sVarName => $sVarVal) {
                $myConfig->saveShopConfVar("str", $sVarName, $sVarVal);
            }
        }
    }

    public function save()
    {
        $this->saveConfVars();
        return;
    }

    protected function getLanguageKeyForOxidPaymentId($oxidPaymentId)
    {
        return 'PAYMILL_PAYMENT_METHOD_' . strtoupper(end(explode('_', $oxidPaymentId, 2)));
    }

    public function uninstallPayments()
    {
        $sQuery = 'DELETE FROM `oxpayments` WHERE `OXID` = "paymill_credit_card"';
        oxDb::getDb()->Execute($sQuery);
        $sQuery = 'DELETE FROM `oxpayments` WHERE `OXID` = "paymill_elv"';
        oxDb::getDb()->Execute($sQuery);
    }

    public function installPayments()
    {

        $this->uninstallPayments();
        $oxidPaymentId = 'paymill_credit_card';

        $aLanguageParams = array_values($this->getConfig()->getConfigParam('aLanguageParams'));

        $sQuery = "INSERT INTO `oxpayments` (`OXID`, `OXACTIVE`, `OXTOAMOUNT`, ";

        $queryLanguageColumnNames = '';
        foreach ($aLanguageParams as $aLanguageParam) {
            if (!empty($queryLanguageColumnNames)) {
                $queryLanguageColumnNames .= ", ";
            }
            if ($aLanguageParam["baseId"] > 0) {
                $queryLanguageColumnNames .= "`OXDESC_" . $aLanguageParam["baseId"] . "` ";
            } else {
                $queryLanguageColumnNames .= "`OXDESC` ";
            }
        }
        $sQuery .= $queryLanguageColumnNames;

        $sQuery .= ") VALUES ('" . $oxidPaymentId . "', 1, 1000000, ";

        $langKey = $this->getLanguageKeyForOxidPaymentId($oxidPaymentId);
        $oxLang = oxLang::getInstance();

        $queryLanguageColumnValues = '';
        foreach ($aLanguageParams as $aLanguageParam) {
            if (!empty($queryLanguageColumnValues)) {
                $queryLanguageColumnValues .= ', ';
            }
            $queryLanguageColumnValues .=
                    oxDb::getDb()->quote($oxLang->translateString($langKey, $aLanguageParam['baseId']));
        }
        $sQuery .= $queryLanguageColumnValues;

        $sQuery .= ")";

        oxDb::getDb()->Execute($sQuery);

        // elv
        $oxidPaymentId = 'paymill_elv';

        $aLanguageParams = array_values($this->getConfig()->getConfigParam('aLanguageParams'));

        $sQuery = "INSERT INTO `oxpayments` (`OXID`, `OXACTIVE`, `OXTOAMOUNT`, ";

        $queryLanguageColumnNames = '';
        foreach ($aLanguageParams as $aLanguageParam) {
            if (!empty($queryLanguageColumnNames)) {
                $queryLanguageColumnNames .= ", ";
            }
            if ($aLanguageParam["baseId"] > 0) {
                $queryLanguageColumnNames .= "`OXDESC_" . $aLanguageParam["baseId"] . "` ";
            } else {
                $queryLanguageColumnNames .= "`OXDESC` ";
            }
        }
        $sQuery .= $queryLanguageColumnNames;

        $sQuery .= ") VALUES ('" . $oxidPaymentId . "', 1, 1000000, ";

        $langKey = $this->getLanguageKeyForOxidPaymentId($oxidPaymentId);
        $oxLang = oxLang::getInstance();

        $queryLanguageColumnValues = '';
        foreach ($aLanguageParams as $aLanguageParam) {
            if (!empty($queryLanguageColumnValues)) {
                $queryLanguageColumnValues .= ', ';
            }
            $queryLanguageColumnValues .=
                    oxDb::getDb()->quote($oxLang->translateString($langKey, $aLanguageParam['baseId']));
        }
        $sQuery .= $queryLanguageColumnValues;

        $sQuery .= ")";
        oxDb::getDb()->Execute($sQuery);
    }

}
