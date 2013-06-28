<?php
$sMetadataVersion = '1.1';
$aModule = array(
    'id'           => 'paymill',
    'title'        => 'Paymill',
    'description'  => 'Paymill Payment',
    'thumbnail'    => 'image/logo.png',
    'version'      => '2.0',
    'author'       => 'Paymill GmbH',
    'url'          => 'http://www.paymill.de',
    'email'        => 'support@paymill.de',
    'extend'       => array(
        'payment' => 'paymill/controllers/paymill_payment',
        'oxpaymentgateway' => 'paymill/controllers/paymill_paymentgateway'
    ),
    'files' => array(
        'PaymentProcessor' => 'paymill/lib/Services/PaymentProcessor.php',
        'paymill_log' => 'paymill/controllers/admin/paymill_log.php'
    ),
    'blocks' => array(
        array('template' => 'page/checkout/payment.tpl', 'block'=>'select_payment', 'file'=>'paymill_select_payment.tpl'),
        array('template' => 'page/checkout/payment.tpl', 'block'=>'checkout_payment_main', 'file'=>'paymill_select_header.tpl'),
        array('template' => 'page/checkout/payment.tpl', 'block'=>'checkout_payment_errors', 'file'=>'paymill_select_error.tpl')
    ),
    'templates' => array(
        'paymill_payment.tpl' => 'paymill/views/azure/tpl/page/checkout/inc/paymill_payment.tpl',
        'paymill_log.tpl' => 'paymill/views/admin/tpl/paymill_log.tpl'
    ),
    'settings' => array(
        array( 'group' => 'main','name' => 'PAYMILL_PRIVATEKEY', 'type' => 'str', 'value' => ''),
        array( 'group' => 'main','name' => 'PAYMILL_PUBLICKEY', 'type' => 'str', 'value' => ''),
        array( 'group' => 'main','name' => 'PAYMILL_ACTIVATE_DEBUG', 'type' => 'bool', 'value' => 'false'),
        array( 'group' => 'main','name' => 'PAYMILL_ACTIVATE_LOGGING', 'type' => 'bool', 'value' => 'false'),
        array( 'group' => 'main','name' => 'PAYMILL_ACTIVATE_FASTCHECKOUT', 'type' => 'bool', 'value' => 'false'),
        array( 'group' => 'main','name' => 'PAYMILL_SHOW_LABEL', 'type' => 'bool', 'value' => 'true'),
    )
);