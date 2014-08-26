<?php 
    /**
     * Options
     *
     */
    class Creativestyle_Newsletterlayer_Model_System_Config_Source_Layerpage
    {

        /**
         * Options getter
         *
         * @return array
         */
        public function toOptionArray()
        {
            return array(
                array('value' => 'all', 'label'=>Mage::helper('adminhtml')->__('All pages')),
                array('value' => 'homepage', 'label'=>Mage::helper('adminhtml')->__('Homepage')),
                array('value' => 'category', 'label'=>Mage::helper('adminhtml')->__('Category page')),
                array('value' => 'productpage', 'label'=>Mage::helper('adminhtml')->__('Productdetail Page'))
            );
        }

    }
?>