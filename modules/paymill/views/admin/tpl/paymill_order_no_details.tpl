[{include file="headitem.tpl" title="YAPITAL"}]

[{ oxmultilang ident="PAYMILL_NODETAILS" }]

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="paymill_order_details">
</form>

[{include file="bottomitem.tpl"}]