<?php

class SomethingDigital_CheckoutSuccess_Model_Observer
{
    const XML_PATH_ENABLE_REPEAT = 'dev/sd_checkoutsuccess/enable_repeat';

    public function prepareSuccessRepeat(Varien_Event_Observer $observer)
    {
        if (!Mage::getStoreConfigFlag(static::XML_PATH_ENABLE_REPEAT) || !Mage::helper('core')->isDevAllowed()) {
            // If this module is off, or dev is disabled for this IP, bail out.
            return $this;
        }

        $session = $this->getCheckoutSession();
        if ($session->getLastSuccessQuoteId() && $session->getLastOrderId()) {
            // We already have a quote/order, use that one.
            return $this;
        } elseif ($session->getLastQuoteId() && $session->getLastOrderId()) {
            // We had a recent order, but we already viewed the success page.
            $session->setLastSuccessQuoteId($session->getLastQuoteId());
            return $this;
        }

        // Try to find the most recent order for this customer.
        $order = $this->getLastOrder();
        if ($order && $order->getId()) {
            $session->setLastSuccessQuoteId($order->getQuoteId());
            $session->setLastQuoteId($order->getQuoteId());
            $session->setLastOrderId($order->getId());
        }

        return $this;
    }

    protected function getLastOrder()
    {
        $customerSession = $this->getCustomerSession();
        if ($customerSession->getCustomerId()) {
            /** @var Mage_Sales_Model_Resource_Order_Collection */
            $orders = Mage::getModel('sales/order')->getCollection();
            $orders->addAttributeToSelect('entity_id');
            $orders->addAttributeToSelect('quote_id');
            $orders->addAttributeToFilter('customer_id', $customerSession->getCustomerId());
            $orders->addAttributeToSort('created_at', 'DESC');

            /** @var Mage_Sales_Model_Order $order */
            $order = $orders->setPageSize(1)->getLastItem();
            return $order;
        }
        return Mage::getModel('sales/order');
    }

    protected function getCheckoutSession()
    {
        /** @var Mage_Checkout_Model_Session */
        $session = Mage::getSingleton('checkout/session');
        return $session;
    }

    protected function getCustomerSession()
    {
        /** @var Mage_Customer_Model_Session $session */
        $session = Mage::getSingleton('customer/session');
        return $session;
    }
}
