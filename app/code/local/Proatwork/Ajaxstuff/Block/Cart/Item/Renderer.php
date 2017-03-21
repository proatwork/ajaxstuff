<?php

class Proatwork_Ajaxstuff_Block_Cart_Item_Renderer extends Mage_Checkout_Block_Cart_Item_Renderer
{
    /**
    * Get item ajax delete url
    *
    * @return string
    */
    public function getAjaxDeleteUrl()
    {
        $Url = parent::getAjaxDeleteUrl();
        return str_replace("checkout/cart","ajaxstuff/cart", $Url);
    }
    /**
     * Get item ajax update url
     *
     * @return string
     */
    public function getAjaxUpdateUrl()
    {
        $Url = parent::getAjaxUpdateUrl();
        return str_replace("checkout/cart","ajaxstuff/cart", $Url);
    }
}