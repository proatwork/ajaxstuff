<?php
/**
 * Created by PhpStorm.
 * User: proatwork
 * Date: 27/10/15
 * Time: 1:43 PM
 */
class Proatwork_Ajaxstuff_Model_System_Config_Backend_Json extends Mage_Core_Model_Config_Data
{
    protected function _afterLoad()
    {
        if (!is_array($this->getValue())) {
            $value = $this->getValue();
            $this->setValue(empty($value) ? false : json_decode($value,true));
        }
    }
    protected function _beforeSave()
    {
        $value = $this->getValue();
        if (is_array($value)) {
            unset($value['__empty']);
            if($value) {
                $this->setValue(json_encode($value));
            } else {
                $this->setValue('');
            }
        }
    }
}