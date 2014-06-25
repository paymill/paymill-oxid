[{assign var="oxConfig" value=$oView->getConfig()}]
<div id="paymentOption_[{$sPaymentID}]" class="payment-option [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]active-payment[{/if}]">
    <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
    <ul class="form">
        <li class="controls controls-row">
            <div class="payment-errors elv pm-mobile" style="display:none;"></div>
            <div id="payment-form-elv">
                <div class="controls controls-row">
                    <input id="paymillElvHolderName" class="paymill_input elv-holdername span3" type="text" size="20" value="[{$paymillElvHolder}]" placeholder="[{ oxmultilang ident="PAGE_CHECKOUT_PAYMENT_ACCOUNTHOLDER" }]" />
                </div>
                <div class="controls controls-row">
                    <input id="paymillElvAccount" class="paymill_input elv-account span3" type="text"  autocomplete="off" size="20" value="[{$paymillElvAccount}]" placeholder="[{ oxmultilang ident="PAGE_CHECKOUT_PAYMENT_ACCOUNT" }] / [{ oxmultilang ident="PAGE_CHECKOUT_PAYMENT_IBAN" }]" />
                </div>
                <div class="controls controls-row">
                    <input id="paymillElvBankCode" class="paymill_input elv-bankcode span3" type="text" autocomplete="off" size="20" value="[{$paymillElvCode}]" placeholder="[{ oxmultilang ident="PAGE_CHECKOUT_PAYMENT_BANKCODE" }] / [{ oxmultilang ident="PAGE_CHECKOUT_PAYMENT_BIC" }]" />
                </div>
            </div>
        </li>

        [{block name="checkout_payment_longdesc"}]
            [{if $paymentmethod->oxpayments__oxlongdesc->value}]
                <li>
                    <div class="payment-desc">
                        [{$paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
                    </div>
                </li>
            [{/if}]
        [{/block}]
    </ul>
</div>