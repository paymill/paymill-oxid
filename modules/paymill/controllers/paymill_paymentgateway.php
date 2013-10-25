<?php

/**
 * paymill_paymentgateway
 *
 * @author     Copyright (c) 2013 PayIntelligent GmbH (http://www.payintelligent.de)
 * @copyright  Copyright (c) 2013 Paymill GmbH (https://www.paymill.com)
 */
class paymill_paymentgateway extends paymill_paymentgateway_parent implements Services_Paymill_LoggingInterface
{

    
    private $_apiUrl = "https://api.paymill.com/v2/";
    
    private $_paymentProcessor;
    
    private $_fastCheckoutData;
    
    /**
     * @overload
     */
    public function executePayment($dAmount, &$oOrder)
    {
        if (!in_array($oOrder->oxorder__oxpaymenttype->rawValue, array("paymill_cc", "paymill_elv"))) {
            return parent::executePayment($dAmount, & $oOrder);
        }

        $this->_iLastErrorNo = null;
        $this->_sLastError = null;
        
        $this->_initializePaymentProcessor($dAmount, $oOrder);
        if ($this->_getPaymentShortCode($oOrder->oxorder__oxpaymenttype->rawValue) === 'cc') {
            $this->_paymentProcessor->setPreAuthAmount((int) oxSession::getVar('paymill_authorized_amount'));
        }
        
        $prop = 'paymill_fastcheckout__paymentid_' + $this->_getPaymentShortCode($oOrder->oxorder__oxpaymenttype->rawValue);
        
        $this->_loadFastCheckoutData();
        if (!oxSession::getVar('paymillShowForm_' . $this->_getPaymentShortCode($oOrder->oxorder__oxpaymenttype->rawValue))) {
            $this->_paymentProcessor->setPaymentId(
                $this->_fastCheckoutData->$prop->rawValue
            );
            
            $this->_paymentProcessor->setClientId(
                $this->_fastcheckoutData->paymill_fastcheckout__clientid->rawValue
            );
        }

        $result = $this->_paymentProcessor->processPayment();
        
        $this->log($result ? 'Payment results in success' : 'Payment results in failure', null);
        if (oxConfig::getInstance()->getShopConfVar('PAYMILL_ACTIVATE_FASTCHECKOUT') == "1" && $result) {
            $paymentColumn = 'paymentID_' . strtoupper($this->_getPaymentShortCode($oOrder->oxorder__oxpaymenttype->rawValue));
            //insert new data
            $this->_fastcheckoutData->assign(
                array(
                    'oxid' => $oOrder->oxorder__oxuserid->rawValue,
                    'clientid' => $this->_paymentProcessor->getClientId(),
                    $paymentColumn => $this->_paymentProcessor->getPaymentId()
                )
            );
        }
        
        $this->_fastcheckoutData->save();
        
        if (oxConfig::getInstance()->getShopConfVar('PAYMILL_SET_PAYMENTDATE')) {
            $this->_setPaymentDate($oOrder);
        }
        
        return $result;
    }
    
    private function _initializePaymentProcessor($dAmount, $oOrder)
    {
        if (!is_null(oxSession::getVar('paymill_token'))) {
            $token = oxSession::getVar('paymill_token');
        } else {
            $oOrder->getSession()->setVar("paymill_error", "No Token was provided");
            return false;
        }
        
        $utf8Name = $this->convertToUtf(
            $oOrder->oxorder__oxbilllname->value . ', ' . $oOrder->oxorder__oxbillfname->value, 
            oxConfig::getInstance()->isUtf()
        );
        
        $this->_paymentProcessor = new Services_Paymill_PaymentProcessor(
            trim(oxConfig::getInstance()->getShopConfVar('PAYMILL_PRIVATEKEY')), 
            $this->_apiUrl, 
            null, 
            array(
                'token' => $token,
                'amount' => (int) round($dAmount * 100),
                'currency' => strtoupper($oOrder->oxorder__oxcurrency->rawValue),
                'name' => $utf8Name,
                'email' => $oOrder->oxorder__oxbillemail->value,
                'description' => 'OrderID: ' . $oOrder->oxorder__oxid . ' - ' . $utf8Name
            ), 
            $this
        );
        
        $this->_paymentProcessor->setSource($this->_getSourceInfo());
        
    }
    
    private function _loadFastCheckoutData()
    {        
        $this->_fastcheckoutData = oxNew('paymill_fastcheckout');
        $this->_fastcheckoutData->load($this->getUser()->getId());
    }

    private function _getPaymentShortCode($paymentCode)
    {
        $paymentType = split('_', $paymentCode);
        
        return $paymentType[1];
    }

    private function _setPaymentDate($oOrder)
    {
        $oDb = oxDb::getDb();
        $sDate = date('Y-m-d H:i:s', oxUtilsDate::getInstance()->getTime());
        $sQ = 'update oxorder set oxpaid=\'' . $sDate . '\' where oxid=' . $oDb->quote($oOrder->getId());
        $oOrder->oxorder__oxorderdate = new oxField($sDate, oxField::T_RAW);
        $oDb->execute($sQ);
    }
    
    private function _getSourceInfo()
    {
        $modul = oxNew('oxModule');
        $modul->load('paymill');
        
        return $modul->getInfo('version') . '_oxid_' . oxConfig::getInstance()->getVersion();
    }
    
    
    

    /**
     * log the gien message
     *
     * @param string $message
     * @param string $debuginfo
     */
    public function log($message, $debuginfo)
    {
        $logfile = dirname(dirname(__FILE__)) . '/log.txt';
        if (oxConfig::getInstance()->getShopConfVar('PAYMILL_ACTIVATE_LOGGING') == "1") {
            $handle = fopen($logfile, 'a+');
            fwrite($handle, "[" . date(DATE_RFC822) . "] " . $message . "\n");
            fclose($handle);
        }
    }

    public function convertToUtf($value, $utfMode)
    {
        if (!$utfMode) {
            $value = utf8_encode($value);
        }

        return $value;
    }

}
