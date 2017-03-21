<?php
/**
 * Created by PhpStorm.
 * User: proatwork
 * Date: 27/10/15
 * Time: 12:02 PM
 */
class Proatwork_Ajaxstuff_Block_Adminhtml_Selectors extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    /**
     * Prepare to rendered (add collumns, change add button)
     *
     */
    protected function _prepareToRender()
    {


        $this->addColumn(
            'css_selector',
            array(
                'label' => Mage::helper('proatwork_ajaxstuff')->__('CSS Selector')
            )
        );
        $this->addColumn(
            'block_name',
            array(
                'label' => Mage::helper('proatwork_ajaxstuff')->__('Block (XML) name')
            )
        );

        // Disables "Add after" button
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('proatwork_ajaxstuff')->__('Add Block');

    }


    //add element id for dependecy (else, <depends> will not works)

    protected function _toHtml()
    {
        return '<div id="'.$this->getElement()->getId().'">'.parent::_toHtml().'</div>';
    }

}