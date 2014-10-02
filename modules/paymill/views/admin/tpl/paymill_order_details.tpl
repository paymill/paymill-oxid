[{include file="headitem.tpl" title="YAPITAL"}]
<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="paymill_order_details">
</form>
[{if $oView->hasError()}]<div>[{ oxmultilang ident="PAYMILL_REFUND_ERROR" }]</div>[{/if}]
[{if $oView->hasSuccess()}]<div>[{ oxmultilang ident="PAYMILL_REFUND_SUCCESS" }]</div>[{/if}]
<div id="liste">
    <h1>[{ oxmultilang ident="PI_PAYMILL_DETAILS" }]</h1>
    [{if $oView->canRefund()}]
    <form name="return_order" id="return" action="[{$oViewConf->getSelfLink()}]" method="post">
        <input type="hidden" name="oxid" value="[{$oView->getEditObjectId()}]">
        <input type="submit" value='[{ oxmultilang ident="PAYMILL_REFUND_ORDER" }]'>
        <input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName()}]">
        <input type="hidden" name="fnc" value="refundTransaction">
        [{$oViewConf->getHiddenSid()}]
    </form>
    [{/if}]
</div>

[{include file="bottomitem.tpl"}]