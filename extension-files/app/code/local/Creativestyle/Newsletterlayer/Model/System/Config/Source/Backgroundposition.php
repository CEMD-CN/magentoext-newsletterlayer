<?php 
    /**
     * Options
     *
     */
    class Creativestyle_Newsletterlayer_Model_System_Config_Source_Backgroundposition
    {

        /**
         * Options getter
         *
         * @return array
         */
        public function toOptionArray()
        {
            return array(
                array('value' => '0 0', 'label'=>Mage::helper('adminhtml')->__('top left')),
                array('value' => '0 50%', 'label'=>Mage::helper('adminhtml')->__('top center')),
                array('value' => '0 100%', 'label'=>Mage::helper('adminhtml')->__('top right')),
                array('value' => '50% 0', 'label'=>Mage::helper('adminhtml')->__('middle left')),
                array('value' => '50% 50%', 'label'=>Mage::helper('adminhtml')->__('middle center')),
                array('value' => '50% 100%', 'label'=>Mage::helper('adminhtml')->__('middle right')),
                array('value' => '100% 0', 'label'=>Mage::helper('adminhtml')->__('bottom left')),
                array('value' => '100% 50%', 'label'=>Mage::helper('adminhtml')->__('bottom center')),
                array('value' => '100% 100%', 'label'=>Mage::helper('adminhtml')->__('bottom right')),
                array('value' => 'custom', 'label'=>Mage::helper('adminhtml')->__('custom'))
            );
        }

    }
?>