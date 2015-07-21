<?php

/**
 * paymill_order_details
 *
 * @copyright  Copyright (c) 2015 PAYMILL GmbH (https://www.paymill.com)
 */
class paymill_order_details extends oxAdminDetails implements Services_Paymill_LoggingInterface
{

    /**
     * Render the yapital order detail template
     *
     * @return string
     */
    public function render()
    {
        parent::render();
        if ($this->_getPaymentSid() === 'paymill_cc' || $this->_getPaymentSid() === 'paymill_elv' ) {
            return 'paymill_order_details.tpl';
        }

        return 'paymill_order_no_details.tpl';
    }

    /**
     * Is capture possible
     *
     * @return boolean
     */
    public function canCapture()
    {
        $transaction = oxNew('paymill_transaction');
        $transaction->load($this->getEditObjectId());

        return is_null($transaction->paymill_transaction__transaction_id->rawValue) && !is_null($transaction->paymill_transaction__preauth_id->rawValue);
    }

    /**
     * Is refund possible
     *
     * @return boolean
     */
    public function canRefund()
    {
        $transaction = oxNew('paymill_transaction');
        $transaction->load($this->getEditObjectId());

        return $this->getEditObject()->getTotalOrderSum() > 0 &&  !is_null($transaction->paymill_transaction__transaction_id->rawValue);
    }

    public function capturePreauth()
    {
        $transaction = oxNew('paymill_transaction');
        $transaction->load($this->getEditObjectId());

        $params = array();
        $params['amount'] = (int)  (int) ($this->_getRefundAmount() * 100);
        $params['currency'] = strtoupper($this->getEditObject()->oxorder__oxcurrency->rawValue);

        $paymentProcessor = new Services_Paymill_PaymentProcessor(
            trim(oxRegistry::getConfig()->getShopConfVar('PAYMILL_PRIVATEKEY')),
            paymill_util::API_ENDPOINT,
            null,
            $params,
            $this
        );

        oxRegistry::getSession()->setVariable('preauth', true);

        $paymentProcessor->setPreauthId($transaction->paymill_transaction__preauth_id->rawValue);

        if (!$paymentProcessor->capture()) {
            oxRegistry::getSession()->setVariable('error', true);
        } else {
            $transaction->assign(array('transaction_id' => $paymentProcessor->getTransactionId()));
            $transaction->save();
            oxRegistry::getSession()->setVariable('success', true);
        }
    }

    /**
     * Get the maximal possible refund amount
     *
     * @return float
     */
    private function _getRefundAmount()
    {
        return $this->getEditObject()->getTotalOrderSum();
    }

    /**
     * Refund the selected paymill transaction
     */
    public function refundTransaction()
    {
        $oxOrder = $this->getEditObject();

        $transaction = oxNew('paymill_transaction');
        $transaction->load($this->getEditObjectId());

        //Create Refund
        $params = array(
            'transactionId' => $transaction->paymill_transaction__transaction_id->rawValue,
            'params' => array('amount' => (int) ($this->_getRefundAmount() * 100))
        );

        $refundsObject = new Services_Paymill_Refunds(
            trim(oxRegistry::getConfig()->getShopConfVar('PAYMILL_PRIVATEKEY')),
            paymill_util::API_ENDPOINT
        );

        oxRegistry::getSession()->setVariable('refund', true);

        try {
            $refund = $refundsObject->create($params);
        } catch (Exception $ex) {

        }

        if (isset($refund['response_code']) && $refund['response_code'] == 20000) {
            $oxOrder->assign(array('oxorder__oxdiscount' => $this->_getRefundAmount()));
            $oxOrder->reloadDiscount(false);
            $oxOrder->recalculateOrder();
            oxRegistry::getSession()->setVariable('success', true);
        } else {
            oxRegistry::getSession()->setVariable('error', true);
        }
    }

    /**
     * Return error flag
     *
     * @return boolean
     */
    public function hasRefundError()
    {
        $flag = false;
        if (oxRegistry::getSession()->hasVariable('error')
                && oxRegistry::getSession()->getVariable('error')
                && oxRegistry::getSession()->hasVariable('refund')
                && oxRegistry::getSession()->getVariable('refund')) {
            $flag = true;
            oxRegistry::getSession()->deleteVariable('error');
            oxRegistry::getSession()->deleteVariable('refund');
        }

        return $flag;
    }

    /**
     * Return error flag
     *
     * @return boolean
     */
    public function hasRefundSuccess()
    {
        $flag = false;
        if (oxRegistry::getSession()->hasVariable('success')
                && oxRegistry::getSession()->getVariable('success')
                && oxRegistry::getSession()->hasVariable('refund')
                && oxRegistry::getSession()->getVariable('refund')) {
            $flag = true;
            oxRegistry::getSession()->deleteVariable('success');
            oxRegistry::getSession()->deleteVariable('refund');
        }

        return $flag;
    }

    /**
     * Return error flag
     *
     * @return boolean
     */
    public function hasCaptureError()
    {
        $flag = false;
        if (oxRegistry::getSession()->hasVariable('error')
                && oxRegistry::getSession()->getVariable('error')
                && oxRegistry::getSession()->hasVariable('preauth')
                && oxRegistry::getSession()->getVariable('preauth')) {
            $flag = true;
            oxRegistry::getSession()->deleteVariable('error');
            oxRegistry::getSession()->deleteVariable('preauth');
        }

        return $flag;
    }

    /**
     * Return error flag
     *
     * @return boolean
     */
    public function hasCaptureSuccess()
    {
        $flag = false;
        if (oxRegistry::getSession()->hasVariable('success')
                && oxRegistry::getSession()->getVariable('success')
                && oxRegistry::getSession()->hasVariable('preauth')
                && oxRegistry::getSession()->getVariable('preauth')) {
            $flag = true;
            oxRegistry::getSession()->deleteVariable('success');
            oxRegistry::getSession()->deleteVariable('preauth');
        }

        return $flag;
    }

    /**
     * Return payment id
     *
     * @return string
     */
    protected function _getPaymentSid()
    {
        if (is_null($this->_paymentSid)) {
            $order = $this->getEditObject();
            $this->_paymentSid = $this->_getPaymentType($order);
        }

        return $this->_paymentSid;
    }

    /**
     * Return payment type of give order
     *
     * @param oxOrder $order
     * @return string
     */
    protected function _getPaymentType($order)
    {
        $data = false;
        if (isset($order)) {
            $data = $order->getPaymentType()->oxuserpayments__oxpaymentsid->value;
        }

        return $data;
    }


    /**
     * Returns editable order object
     *
     * @return oxorder|null
     */
    public function getEditObject()
    {
        $orderId = $this->getEditObjectId();

        if (is_null($this->_oEditObject) && isset($orderId) && $orderId != "-1") {
            $this->_oEditObject = oxNew("oxorder");
            $this->_oEditObject->load($orderId);
        }

        return $this->_oEditObject;
    }

    /**
     * log the given message
     *
     * @param string $message
     * @param string $debuginfo
     *
     * @todo  remove this use paymill_logger instead
     */
    public function log($message, $debuginfo)
    {
        if (oxRegistry::getConfig()->getShopConfVar('PAYMILL_ACTIVATE_LOGGING')) {
            $logging = oxNew('paymill_logging');
            $logging->assign(array(
                'identifier' => $this->getSession()->getVariable('paymill_identifier'),
                'debug' => $debuginfo,
                'message' => $message,
                'date' => date('Y-m-d H:i:s', oxRegistry::get('oxUtilsDate')->getTime())
            ));

            $logging->save();
        }
    }
}
