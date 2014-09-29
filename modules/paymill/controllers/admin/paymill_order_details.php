<?php

class paymill_order_details extends oxAdminDetails
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
     * Refund the selected paymill transaction
     */
    public function refundTransaction()
    {
        
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
}
