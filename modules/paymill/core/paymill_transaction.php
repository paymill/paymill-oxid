<?php

/**
 * paymill_fastcheckout
 *
 * @copyright  Copyright (c) 2015 PAYMILL GmbH (https://www.paymill.com)
 */
class paymill_transaction extends oxBase
{
    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'paymill_transaction';

    /**
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->init($this->_sClassName);
    }
}
