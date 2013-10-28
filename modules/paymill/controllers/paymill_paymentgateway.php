<?php

/**
 * paymill_paymentgateway
 *
 * @author     Copyright (c) 2013 PayIntelligent GmbH (http://www.payintelligent.de)
 * @copyright  Copyright (c) 2013 Paymill GmbH (https://www.paymill.com)
 */
class paymill_paymentgateway extends paymill_paymentgateway_parent implements Services_Paymill_LoggingInterface
{
    private $_apiUrl;
    
    private $_paymentProcessor;
    
    private $_fastCheckoutData;
    
    private $_clients;
    
    private $_token;
    
    /**
     * @overload
     */
    public function executePayment($dAmount, &$oOrder)
    {
        if (!in_array($oOrder->oxorder__oxpaymenttype->rawValue, array("paymill_cc", "paymill_elv"))) {
            return parent::executePayment($dAmount, $oOrder);
        }
        
        if (oxSession::hasVar('paymill_token')) {
            $this->_token = oxSession::getVar('paymill_token');
        } else {
            $oOrder->getSession()->setVar("paymill_error", "No Token was provided");
            return false;
        }
        
        $this->_apiUrl = paymill_util::API_ENDPOINT;
        
        $this->_iLastErrorNo = null;
        $this->_sLastError = null;
        
        if (!$this->_initializePaymentProcessor($dAmount, $oOrder)) {
            return false;
        }
        
        if ($this->_getPaymentShortCode($oOrder->oxorder__oxpaymenttype->rawValue) === 'cc') {
            $this->_paymentProcessor->setPreAuthAmount((int) oxSession::getVar('paymill_authorized_amount'));
        }
        
        $prop = 'paymill_fastcheckout__paymentid_' + $this->_getPaymentShortCode($oOrder->oxorder__oxpaymenttype->rawValue);
        
        $this->_loadFastCheckoutData();
        $this->_existingClientHandling($oOrder);
        
        if ($this->_token === 'dummyToken') {
            $this->_paymentProcessor->setPaymentId(
                $this->_fastCheckoutData->$prop->rawValue
            );
        }
        
        $result = $this->_paymentProcessor->processPayment();
        
        $this->log($result ? 'Payment results in success' : 'Payment results in failure', null);
        
        if ($result) {
            $saveData = array(
                'oxid' => $oOrder->oxorder__oxuserid->rawValue,
                'clientid' => $this->_paymentProcessor->getClientId()
            );
            
            if (oxConfig::getInstance()->getShopConfVar('PAYMILL_ACTIVATE_FASTCHECKOUT')) {
                $paymentColumn = 'paymentID_' . strtoupper($this->_getPaymentShortCode($oOrder->oxorder__oxpaymenttype->rawValue));
                $saveData[$paymentColumn] = $this->_paymentProcessor->getPaymentId();
            }
            
            $this->_fastcheckoutData->assign($saveData);
            $this->_fastcheckoutData->save();
        }
        
        if (oxConfig::getInstance()->getShopConfVar('PAYMILL_SET_PAYMENTDATE')) {
            $this->_setPaymentDate($oOrder);
        }
        
        return $result;
    }
    
    private function _existingClientHandling($oOrder)
    {
        $clientId = $this->_fastcheckoutData->paymill_fastcheckout__clientid->rawValue;
        if (!empty($clientId)) {
            $this->_clients = new Services_Paymill_Clients(
                trim(oxConfig::getInstance()->getShopConfVar('PAYMILL_PRIVATEKEY')),
                $this->_apiUrl
            );
            
            $client = $this->_clients->getOne($clientId);
            if ($oOrder->oxorder__oxbillemail->value !== $client['email']) {
                $this->_clients->update(
                    array(
                        'id' => $clientId,
                        'email' => $oOrder->oxorder__oxbillemail->value
                    )
                );
            }
            
            $this->_paymentProcessor->setClientId($clientId);
        }
    }
    
    private function _initializePaymentProcessor($dAmount, $oOrder)
    {   
        $utf8Name = $this->convertToUtf(
            $oOrder->oxorder__oxbilllname->value . ', ' . $oOrder->oxorder__oxbillfname->value, 
            oxConfig::getInstance()->isUtf()
        );
        
        $this->_paymentProcessor = new Services_Paymill_PaymentProcessor(
            trim(oxConfig::getInstance()->getShopConfVar('PAYMILL_PRIVATEKEY')), 
            $this->_apiUrl, 
            null, 
            array(
                'token' => $this->_token,
                'amount' => (int) round($dAmount * 100),
                'currency' => strtoupper($oOrder->oxorder__oxcurrency->rawValue),
                'name' => $utf8Name,
                'email' => $oOrder->oxorder__oxbillemail->value,
                'description' => 'OrderID: ' . $oOrder->oxorder__oxid . ' - ' . $utf8Name
            ), 
            $this
        );
        
        $this->_paymentProcessor->setSource($this->_getSourceInfo());
        
        return true;
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
     * log the given message
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
