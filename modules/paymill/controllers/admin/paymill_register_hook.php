<?php

class paymill_register_hook extends paymill_log_abstract
{
    public function render()
    {
        parent::render();

        $this->addTplParam(
            'hookUrl',
            $this->getConfig()->getSslShopUrl() . 'index.php?cl=paymill_hooks'
        );

        return 'paymill_register_hook.tpl';
    }

    public function registerHookPoint()
    {
        $oxConfig = oxRegistry::getConfig();
        $webhooks = new Services_Paymill_Webhooks(
            trim($oxConfig->getShopConfVar('PAYMILL_PRIVATEKEY')),
            paymill_util::API_ENDPOINT
        );

        $webhooks->create(array(
            "url" => $oxConfig->getRequestParameter('hook_url'),
            "event_types" => array('refund.succeeded', 'chargeback.executed')
        ));
    }
}
