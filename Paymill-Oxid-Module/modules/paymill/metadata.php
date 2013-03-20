<?php

$sMetadataVersion = '1.1';
$aModule = array(
    'id' => 'paymill',
    'title' => 'Paymill',
    'description' => 'Paymill Payment',
    'thumbnail' => 'logo.jpeg',
    'version' => '1.5',
    'author' => 'Paymill GmbH',
    'url' => 'http://www.paymill.de',
    'email' => 'support@paymill.de',
    'extend' => array(
        'order' => 'paymill/controllers/paymill__order',
        'payment' => 'paymill/controllers/paymill__payment'
    ),
    'files' => array(
        'paymill_configuration' => 'paymill/controllers/admin/paymill_configuration.php'
    ),
    'blocks' => array(
        array(
            'template' => 'page/checkout/payment.tpl',
            'block' => 'select_payment',
            'file' => 'select_payment.tpl'
        )
    ),
    'templates' => array(
        'paymill_configuration.tpl' => 'paymill/views/admin/tpl/paymill_configuration.tpl',
        'paymill_credit_card.tpl' => 'paymill/views/azure/tpl/page/checkout/inc/paymill_credit_card.tpl',
        'paymill_elv.tpl' => 'paymill/views/azure/tpl/page/checkout/inc/paymill_elv.tpl'
    )
);
