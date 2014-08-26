<?php
class Creativestyle_Newsletterlayer_Block_Subscribe extends Mage_Newsletter_Block_Subscribe
{
    public function isNeedToDisplayLayer() {
        $isEnabled = Mage::helper('newsletterlayer')->getGeneralConfig('enabled');
        $controller = $this->getRequest()->getControllerName();
        $layerPage = Mage::helper('newsletterlayer')->getGeneralConfig('layerpage');

        $all = false;
        if($layerPage == 'all') {
            $all = true;
        }
        $home = false;
        if($layerPage == 'homepage' && Mage::getBlockSingleton('page/html_header')->getIsHomePage()) {
            $home = true;
        }
        $category = false;
        if($layerPage == 'category' && $controller == 'category') {
            $category = true;
        }
        $pdp = false;
        if($layerPage == 'productpage' && $controller == 'product') {
            $pdp = true;
        }

        $condition = ($isEnabled && !$this->getIsSubscribed() && !$this->getIsCookieSet() && ($all == true || $home == true || $category == true || $pdp == true));
        return $condition;
    }

    public function getSubscriber() {
        $subscriber = false;
        $session = Mage::getSingleton('customer/session');
        if($session->isLoggedIn()) {
            $email = $session->getCustomer()->getEmail(); 
            $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($email); 
        }

        return $subscriber;
    }

    public function getIsSubscribed() {
        if($this->getSubscriber()) {
            return $this->getSubscriber()->isSubscribed();
        } else {
            return false;
        }
    }

    public function getCmsBlock() {
    	$blockId = Mage::getModel('cms/block')->load(Mage::helper('newsletterlayer')->getDesignConfig('cmsblock'));
    	if($blockId != '') {
    		return $this->getLayout()->createBlock('cms/block')->setBlockId($blockId->getIdentifier())->toHtml();
    	} else {
    		return;
    	}
    }

    public function getAjaxLoaderUrl() {
    	$indicator = Mage::helper('newsletterlayer')->getDesignConfig('indicator');
    	if($indicator == '') {
    		$indicator = $this->getSkinUrl('creativestyle/newsletterlayer/img/ajax-loading.gif');
    	} else {
            $indicator = Mage::getBaseUrl('media').'newsletterlayer/'.$indicator;
        }

    	return $indicator;
    }

    public function getCookieLifetime() {
    	$cookiesEnabled = Mage::helper('newsletterlayer')->getCookiesConfig('cookiesenabled');
		$cookieLifetime = Mage::helper('newsletterlayer')->getCookiesConfig('cookielifetime');
		if($cookiesEnabled) {
			if($cookieLifetime == '') {
				$cookieLifetime = 7;
			}
		} else {
			$cookieLifetime = 0;
		}

		return $cookieLifetime;
    }

    public function getNlFormActionUrl() {
        if($this->getIsAjax()) {
            return $this->getUrl('newsletter/subscriber/new', array('_secure' => Mage::app()->getStore()->isCurrentlySecure()));
        } else {
            return $this->getFormActionUrl();
        }
    }

    public function getIsCookieSet() {
        return Mage::getModel('core/cookie')->get('newsletterLayer');
    }

    public function getStyle() {
        $contentWidth = Mage::helper('newsletterlayer')->getDesignConfig('contentwidth');
        $contentHeight = Mage::helper('newsletterlayer')->getDesignConfig('contentheight');
        $backgroundColor = Mage::helper('newsletterlayer')->getDesignConfig('layerbgcolor');
        $backgroundImage = Mage::helper('newsletterlayer')->getDesignConfig('layerbgimage');
        $backgroundRepeat = Mage::helper('newsletterlayer')->getDesignConfig('layerbgrepeat');
        $backgroundPosition = Mage::helper('newsletterlayer')->getDesignConfig('layerbgposition');

        if($contentWidth != '') {
            $contentWidth = 'width: '.$contentWidth.'px;';
        }
        if($contentHeight != '') {
            $contentHeight = 'height: '.$contentHeight.'px;';
        }
        if($backgroundColor != '') {
            $backgroundColor = 'background-color: '.$backgroundColor.';';
        }

        if($backgroundRepeat == 1) {
            $backgroundRepeat = 'background-repeat: repeat;';
        } else {
            $backgroundRepeat = 'background-repeat: no-repeat;';
        }

        if($backgroundPosition == 'custom') {
            $backgroundPosition = 'background-position: '.Mage::helper('newsletterlayer')->getDesignConfig('layerbgcustomposition').';';
        } else {
            $backgroundPosition = 'background-position: '.$backgroundPosition.';';
        }

        if($backgroundImage != '') {
            $backgroundImage = 'background-image: url('.Mage::getBaseUrl('media').'newsletterlayer/'.$backgroundImage.');';
        } else {
            $backgroundRepeat = false;
            $backgroundPosition = false;
            $backgroundCustomPosition = false;
        }

        $style = $contentWidth . $contentHeight . $backgroundColor . $backgroundImage . $backgroundRepeat . $backgroundPosition;
        return $style;
    }
}
