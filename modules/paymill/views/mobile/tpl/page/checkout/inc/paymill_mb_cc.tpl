[{assign var="oxConfig" value=$oView->getConfig()}]
<div id="paymentOption_[{$sPaymentID}]" class="payment-option [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]active-payment[{/if}]">
    <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>

    <ul class="form">
        <p class="payment-errors cc pm-mobile" style="display:none;"></p>
        <div>
            <ul id="paymill_brands" class="pm-mobile">
                [{foreach key=brandsId from=$paymillBrands item=cardBrand name=paymillBrandSelect}]
                    <li class="paymill-card-number-[{$cardBrand}]"></li>
                [{/foreach}]
            </ul>
        </div>
        <li>
            <input id="paymillCardNumber" class="paymill_input card-number span3" type="text" size="20" value="[{$paymillCcLastFour}]" placeholder="[{ oxmultilang ident="PAGE_CHECKOUT_PAYMENT_NUMBER" }]" />
        </li>
        <li>
            <input id="paymillCardHolderName" class="paymill_input card-holdername span3" type="text" size="20" value="[{$paymillCcCardHolder}]" placeholder="[{ oxmultilang ident="PAGE_CHECKOUT_PAYMENT_HOLDERNAME" }]" />
        </li>
        <li>
            <label class="card-expiry-label">[{ oxmultilang ident="PAGE_CHECKOUT_PAYMENT_VALIDUNTIL" }]:</label>
            <br>
            <select id="paymillCardExpiryMonth" class="paymill_input card-expiry-month pm-mobile">
                <option value="1">1 - [{ oxmultilang ident="PAGE_CHECKOUT_PAYMENT_MONTH_JAN" }]</option>
                <option value="2">2 - [{ oxmultilang ident="PAGE_CHECKOUT_PAYMENT_MONTH_FEB" }]</option>
                <option value="3">3 - [{ oxmultilang ident="PAGE_CHECKOUT_PAYMENT_MONTH_MAR" }]</option>
                <option value="4">4 - [{ oxmultilang ident="PAGE_CHECKOUT_PAYMENT_MONTH_APR" }]</option>
                <option value="5">5 - [{ oxmultilang ident="PAGE_CHECKOUT_PAYMENT_MONTH_MAY" }]</option>
                <option value="6">6 - [{ oxmultilang ident="PAGE_CHECKOUT_PAYMENT_MONTH_JUN" }]</option>
                <option value="7">7 - [{ oxmultilang ident="PAGE_CHECKOUT_PAYMENT_MONTH_JUL" }]</option>
                <option value="8">8 - [{ oxmultilang ident="PAGE_CHECKOUT_PAYMENT_MONTH_AUG" }]</option>
                <option value="9">9 - [{ oxmultilang ident="PAGE_CHECKOUT_PAYMENT_MONTH_SEP" }]</option>
                <option value="10">10 - [{ oxmultilang ident="PAGE_CHECKOUT_PAYMENT_MONTH_OCT" }]</option>
                <option value="11">11 - [{ oxmultilang ident="PAGE_CHECKOUT_PAYMENT_MONTH_NOV" }]</option>
                <option value="12">12 - [{ oxmultilang ident="PAGE_CHECKOUT_PAYMENT_MONTH_DEC" }]</option>
            </select>
            /
            <select id="paymillCardExpiryYear" class="paymill_input card-expiry-year pm-mobile">
                [{foreach from=$oView->getCreditYears() item=year}]
                [{if $year eq $paymillCcExpireYear}]
                <option selected="selected">[{$year}]</option>
                [{else}]
                <option>[{$year}]</option>
                [{/if}]
                [{/foreach}]
            </select>
        </li>
        <li>
            <input id="paymillCardCvc" class="paymill_input card-cvc span3" type="text" size="4" value="[{$paymillCcCvc}]" placeholder="[{ oxmultilang ident="PAGE_CHECKOUT_PAYMENT_SECURITYCODE" }]" />
            <div class="note">[{ oxmultilang ident="PAGE_CHECKOUT_PAYMENT_CC_TOOLTIP" }]</div>
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