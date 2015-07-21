<?php

/**
 * paymill_log_entry
 *
 * @copyright  Copyright (c) 2015 PAYMILL GmbH (https://www.paymill.com)
 */
class paymill_log_entry extends paymill_log_abstract
{
    public function render()
    {
        $oxConfig = oxRegistry::getConfig();
        $entryId = $oxConfig->getRequestParameter('entryId');

        $this->addTplParam(
            'debug',
            $this->_getEntry($entryId)->paymill_logging__debug->rawValue
        );

        return 'paymill_log_entry.tpl';
    }

    protected function _getEntry($entryId)
    {
        $data = oxNew('paymill_logging');
        $data->load($entryId);

        return $data;
    }
}
