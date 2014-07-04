PAYMILL - OXID
==============

Payment module for OXID Version 4.7.x (ce, pe) and 4.8.x (ce, pe)

Download the module here: https://github.com/paymill/paymill-oxid/archive/master.zip

- Merge the content of the PAYMILL-OXID-Module directory with your OXID installation.
- Clear the OXID tmp folder.
- In your administration backend activate the Paymill plugin.
- Go to the configuration section where you can insert your private and public key (which you can find in your Paymill cockpit [https://app.paymill.de/](https://app.paymill.de/ "Paymill cockpit")).
- In the main menu goto **PAYMILL > Checklist**; The checklist allows you to verify that the module has been successfully configured. It also fixes missing tables, block etc.

# Activate PAYMILL Payment

To activate PAYMILL payment follow these steps:

- In the main menu goto **Shop Settings > Payment Methods**
- Choose the payment method you want to activate
- Click on **Assign User Groups** and assign the right user groups
- Go to tab **Country**, click on **Assign Countries**, and assign the right countries
- In the main menu goto **Shop Settings > Shipping Methods**
- Choose a shipping type (e.g. **Standard**) and go to tab **Payment**
- Click on **Assign Payment Methods** and assign the payment method
- Repeat last 2 steps for other shipping types

# Update
If you want to update from an version earlier than 2.1 (starting from 2.0.0) you have to run the update.sql first.

# Template-Support

- Azure-template is supported by default.
- To support a custom template based on Azure, adapt the template structure within the modules/paymill/out/blocks directory to your custom theme. The files most interesting for you are 'paymill_select_payment.tpl and 'paymill_select_header.tpl'.

# Error handling

In case of any errors turn on the debug mode in the PAYMILL module settings.
Open the javascript console in your browser and check the debug messages during the checkout process.

# Logging

You can access the logging within your administration backend under **PAYMILL > PAYMILL log**

# Notes about the payment process

The payment is processed when an order is placed in the shop frontend.

Fast Checkout: Fast checkout can be enabled by selecting the option in the PAYMILL Basic Settings. If any customer completes a purchase while the option is active this customer will not be asked for data again. Instead a reference to the customer data will be saved allowing comfort during checkout.

# Notes about direct debit (ELV) Prenotification
Because the invoice pdf cannot be automatically extended without breaking custom invoices, we decided to not extend the invoice pdf. To add prenotification to the invoice manually add following snippet:

- Open "Shoproot/modules/invoicepdf/myorder.php" or for OXID 4.8 and newer "Shoproot/modules/oe/invoicepdf/myorder.php" in your preferred editor.
- Change the following lines:

Old:
```php
protected function _setPayUntilInfo( &$iStartPos )
{
    $text = $this->_oData->translate( 'ORDER_OVERVIEW_PDF_PAYUPTO' ).date( 'd.m.Y', mktime( 0, 0, 0, date ( 'm' ), date ( 'd' ) + 7, date( 'Y' ) ) );
    $this->font( $this->getFont(), '', 10 );
    $this->text( 15, $iStartPos + 4, $text );
    $iStartPos += 4;
}
```

New:
```php
protected function _setPayUntilInfo( &$iStartPos )
{
    // PAYMILL Start
    $oPayment = oxNew('oxpayment');
    $oPayment->loadInLang($this->_oData->getSelectedLang(), $this->_oData->oxorder__oxpaymenttype->value);

    if ($oPayment->oxpayments__oxid->value == "paymill_elv") {
        $translatedText = $this->_oData->translate('PAYMILL_PRENOTIFICATION_TEXT');

        $myConfig = oxRegistry::getConfig();
        $daysUntilWithdraw = $myConfig->getConfigParam('PAYMILL_PRENOTIFICATION');
        $orderDate = $this->_oData->oxorder__oxorderdate->value;
        $dateTime = new DateTime($orderDate);
        $dateTime->modify('+' . $daysUntilWithdraw . ' day');
        $dateFormat = $this->_oData->translate('PAYMILL_DATE_FORMAT');
        $date = $dateTime->format($dateFormat);

        $text = $translatedText . ' ' . $date;
    } else {
    // PAYMILL END
        $text = $this->_oData->translate( 'ORDER_OVERVIEW_PDF_PAYUPTO' ).date( 'd.m.Y', mktime( 0, 0, 0, date ( 'm' ), date ( 'd' ) + 7, date( 'Y' ) ) );
    // PAYMILL START
    }
    // PAYMILL END

        $this->font( $this->getFont(), '', 10 );
        $this->text( 15, $iStartPos + 4, $text );
        $iStartPos += 4;
}
```