<?php

require_once 'Mage/Newsletter/controllers/SubscriberController.php';

class Creativestyle_Newsletterlayer_SubscriberController extends Mage_Newsletter_SubscriberController
{
    /**
      * New subscription action
      */
    public function newAction()
    {
        
        $isAjax = (int)$this->getRequest()->isAjax();

        if($isAjax) {
            if ($this->getRequest()->isPost() && $this->getRequest()->getPost('email')) {
                $session            = Mage::getSingleton('core/session');
                $customerSession    = Mage::getSingleton('customer/session');
                $email              = (string) $this->getRequest()->getPost('email');

                try {
                    if (!Zend_Validate::is($email, 'EmailAddress')) {
                        $this->_setBody('error-msg',$this->__('Please enter a valid email address.'));
                        return;
                    }

                    if (Mage::getStoreConfig(Mage_Newsletter_Model_Subscriber::XML_PATH_ALLOW_GUEST_SUBSCRIBE_FLAG) != 1 &&
                        !$customerSession->isLoggedIn()) {
                        $this->_setBody('error-msg',$this->__('Sorry, but administrator denied subscription for guests. Please <a href="%s">register</a>.', Mage::helper('customer')->getRegisterUrl()));
                        return;
                    }

                    $ownerId = Mage::getModel('customer/customer')
                        ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                        ->loadByEmail($email)
                        ->getId();
                    if ($ownerId !== null && $ownerId != $customerSession->getId()) {
                        $this->_setBody('error-msg',$this->__('This email address is already assigned to another user.'));
                        return;
                    }

                    $status = Mage::getModel('newsletter/subscriber')->subscribe($email);
                    if ($status == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE) {
                        $this->_setBody('error-msg',$this->__('Confirmation request has been sent.'));
                    } else {
                        $this->_setBody('success-msg',$this->__('Thank you for your subscription.'));
                    }
                }
                catch (Mage_Core_Exception $e) {
                    $this->_setBody('error-msg',$this->__('There was a problem with the subscription: %s', $e->getMessage()));
                }
                catch (Exception $e) {
                    $this->_setBody('error-msg',$this->__('There was a problem with the subscription.'));
                }
            } else {
                $this->_setBody('error-msg',$this->__('Please enter a valid email address.'));
            }
            return;

        } else {

            if ($this->getRequest()->isPost() && $this->getRequest()->getPost('email')) {
                $session            = Mage::getSingleton('core/session');
                $customerSession    = Mage::getSingleton('customer/session');
                $email              = (string) $this->getRequest()->getPost('email');

                try {
                    if (!Zend_Validate::is($email, 'EmailAddress')) {
                        Mage::throwException($this->__('Please enter a valid email address.'));
                    }

                    if (Mage::getStoreConfig(Mage_Newsletter_Model_Subscriber::XML_PATH_ALLOW_GUEST_SUBSCRIBE_FLAG) != 1 && 
                        !$customerSession->isLoggedIn()) {
                        Mage::throwException($this->__('Sorry, but administrtaor denied subscription for guests. Please <a href="%s">register</a>.', Mage::helper('customer')->getRegisterUrl()));
                    }

                    $ownerId = Mage::getModel('customer/customer')
                            ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                            ->loadByEmail($email)
                            ->getId();
                    if ($ownerId !== null && $ownerId != $customerSession->getId()) {
                        Mage::throwException($this->__('This address email is already assigned to another user.'));
                    }

                    $status = Mage::getModel('newsletter/subscriber')->subscribe($email);
                    if ($status == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE) {
                        $session->addSuccess($this->__('Confirmation request has been sent.'));
                        $customerSession->setEmail($email);
                    }
                    else {
                        $session->addSuccess($this->__('Thank you for your subscription.'));
                        $customerSession->setEmail($email);
                    }
                }
                catch (Mage_Core_Exception $e) {
                    $session->addException($e, $this->__('There was a problem with the subscription: %s', $e->getMessage()));
                }
                catch (Exception $e) {
                    $session->addException($e, $this->__('There was a problem with the subscription.'));
                }
            }

            $this->_redirectReferer();
        }
    }

    private function _setBody($class,$message) {
        $this->getResponse()->setBody(
            Zend_Json::encode(array('class'=>$class,'message'=>$message))
        );
    }
}