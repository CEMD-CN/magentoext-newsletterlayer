<?php

class Creativestyle_Newsletterlayer_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getGeneralConfig($opt)
    {
        return Mage::getStoreConfig(sprintf('newsletterlayer/general/%s', $opt));
    }

    public function getDesignConfig($opt)
    {
        return Mage::getStoreConfig(sprintf('newsletterlayer/design/%s', $opt));
    }

    public function getCookiesConfig($opt)
    {
        return Mage::getStoreConfig(sprintf('newsletterlayer/cookies/%s', $opt));
    }
}