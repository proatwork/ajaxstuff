<?php
class Proatwork_Ajaxstuff_Helper_Cart extends Mage_Checkout_Helper_Cart
{
    public function getAddUrl($product, $additional = array())
    {
        $Url = parent::getAddUrl($product, $additional = array());
        return str_replace("checkout/cart","ajaxstuff/cart", $Url);
    }
}