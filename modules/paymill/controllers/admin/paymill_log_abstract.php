<?php

abstract class paymill_log_abstract extends oxAdminView
{
    public function render()
    {
        parent::render();
        $this->addTplParam('listUrl', $this->_getAdminUrl() . '&cl=paymill_log_list');
    }

    protected function _getAdminUrl()
    {
        $oxConfig = oxRegistry::getConfig();
        return $oxConfig->getShopUrl(null, true)
                . 'admin/index.php?stoken=' . $oxConfig->getRequestParameter('stoken')
                . '&force_admin_sid=' . $oxConfig->getRequestParameter('force_admin_sid');
    }
}