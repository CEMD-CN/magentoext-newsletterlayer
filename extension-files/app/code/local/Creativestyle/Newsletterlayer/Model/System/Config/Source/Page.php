<?php 
class Creativestyle_Newsletterlayer_Model_System_Config_Source_Page
{
    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->_options = Mage::getResourceModel('cms/block_collection')
                ->load()
                ->toOptionArray();
            array_unshift($this->_options, array('value'=>'', 'label'=>Mage::helper('catalog')->__('Please select a static block ...')));
        }
        return $this->_options;
    }
}