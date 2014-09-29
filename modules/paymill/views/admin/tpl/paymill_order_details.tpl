[{include file="headitem.tpl" title="YAPITAL"}]
<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="paymill_order_details">
</form>
<div id="liste">
    <h1>[{ oxmultilang ident="PI_PAYMILL_DETAILS" }]</h1>
    <form name="return_order" id="return" action="[{$oViewConf->getSelfLink()}]" method="post">
        <input type="hidden" name="oxid" value="[{$oView->getEditObjectId()}]">
        <input type="submit" value='[{ oxmultilang ident="PAYMILL_REFUND_ORDER" }]'>
        <input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName()}]">
        <input type="hidden" name="fnc" value="refundTransaction">
        [{$oViewConf->getHiddenSid()}]
    </form>
</div>

[{include file="bottomitem.tpl"}]