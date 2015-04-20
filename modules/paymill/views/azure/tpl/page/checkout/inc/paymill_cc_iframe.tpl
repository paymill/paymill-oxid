[{assign var="oxConfig" value=$oView->getConfig()}]
<dl>
    <dt>
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
        <label for="payment_[{$sPaymentID}]">
            <b>[{ $paymentmethod->oxpayments__oxdesc->value}]</b>
        </label>
    </dt>
    <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
        <ul class="form">
            <li class="controls controls-row">
                <p class="payment-errors cc" style="display:none;"></p>
                <div>
                    <ul id="paymill_brands">
                        [{foreach key=brandsId from=$paymillBrands item=cardBrand name=paymillBrandSelect}]
                            <li class="paymill-card-number-[{$cardBrand}]"></li>
                        [{/foreach}]
                    </ul>
                </div>
                <div id="payment-form-cc">
                </div>
            </li>
        </ul>
        [{block name="checkout_payment_longdesc"}]
            [{if $paymentmethod->oxpayments__oxlongdesc->value}]
                <div class="desc">
                    [{$paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
                </div>
            [{/if}]
        [{/block}]
    </dd>
</dl>