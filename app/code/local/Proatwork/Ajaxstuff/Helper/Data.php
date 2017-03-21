<?php
/**
 * Created by PhpStorm.
 * User: proatwork
 * Date: 26/10/15
 * Time: 4:48 PM
 */ 
class Proatwork_Ajaxstuff_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getBlocks()
    {
        $blocks = Mage::getStoreConfig('ajaxstuff/settings/minicart');
        $blocks = json_decode($blocks, true);
        if ($blocks) {
            $blocks = array_unique($blocks, SORT_REGULAR);
        }
        return $blocks;
    }
    public function getBlocknames()
    {
        $blocks = $this->getBlocks();
        foreach($blocks as $index => $block){
            $blocknames[$index] = $block['block_name'];
        }
        $blocknames = array_unique($blocknames);
        return $blocknames;
    }
    public function getCSSSelectors()
    {
        $blocks = $this->getBlocks();
        $selectors = array();
        if ($blocks) {
            foreach($blocks as $block){
                $selectors[$block['block_name']][] = $block['css_selector'];
            }
        }
        return json_encode($selectors, JSON_PRETTY_PRINT);
    }
    public function afterAJAXCart(){
        return Mage::getStoreConfig('ajaxstuff/settings/ajaxcart_after');
    }
}