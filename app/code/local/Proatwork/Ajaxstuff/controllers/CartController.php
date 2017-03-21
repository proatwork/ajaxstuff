<?php
require_once Mage::getModuleDir('controllers', 'Mage_Checkout') . DS . 'CartController.php';
class Proatwork_Ajaxstuff_CartController extends Mage_Checkout_CartController
{
    private function sendJSON($status, $message)
    {
        $response['status'] = $status;
        $response['message'] = $message;
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }
    public function addAction()
    {
        $cart   = $this->_getCart();
        $params = $this->getRequest()->getParams();
        $response = array();
        if (!$this->_validateFormKey()) {
            $this->sendJSON('ERROR', $this->__('Invalid form key'));
            return;
        }
        try {
            if (isset($params['qty'])) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                $params['qty'] = $filter->filter($params['qty']);
            }
            $product = $this->_initProduct();
            $related = $this->getRequest()->getParam('related_product');
            /**
             * Check product availability
             */
            if (!$product) {
                $this->sendJSON('ERROR', $this->__('Unable to find Product ID'));
                return;
            }
            $cart->addProduct($product, $params);
            if (!empty($related)) {
                $cart->addProductsByIds(explode(',', $related));
            }
            $cart->save();
            $this->_getSession()->setCartWasUpdated(true);
            Mage::dispatchEvent('checkout_cart_add_product_complete',
                array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
            );
            if (!$this->_getSession()->getNoCartRedirect(true)) {
                if (!$cart->getQuote()->getHasError()){
                    $message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->escapeHtml($product->getName()));
                    $response['status'] = 'SUCCESS';
                    $response['message'] = $message;
                    $this->loadLayout();
                    $blocks = Mage::helper('proatwork_ajaxstuff')->getBlocknames();
                    foreach($blocks as $block){
                        if(!$this->getLayout()->getBlock($block)){
                            $response = $block . ' - This blockname does not exist';
                            $this->sendJSON('ERROR', $response);
                            return;
                        } else {
                            $response['blocks'][$block] = $this->getLayout()->getBlock($block)->toHtml();
                        }
                    }
                    $response['qty'] = $this->_getCart()->getSummaryQty();
                }
            }
        } catch (Mage_Core_Exception $e) {
            $msg = "";
            if ($this->_getSession()->getUseNotice(true)) {
                $msg = $e->getMessage();
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $msg .= $message.'<br/>';
                }
            }
            $this->sendJSON('ERROR', $msg);
            return;
        } catch (Exception $e) {
            $this->sendJSON('ERROR', $this->__('Cannot add the item to shopping cart.'));
            Mage::logException($e);
            return;
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }
    /**
     * Minicart delete action
     */
    public function ajaxDeleteAction()
    {
        if (!$this->_validateFormKey()) {
            Mage::throwException('Invalid form key');
        }
        $id = (int) $this->getRequest()->getParam('id');
        $result = array();
        if ($id) {
            try {
                $this->_getCart()->removeItem($id)->save();
                $result['qty'] = $this->_getCart()->getSummaryQty();
                $this->loadLayout();
                $result['content'] = $this->getLayout()->getBlock('minicart_head')->toHtml();
                $result['success'] = 1;
                $result['message'] = $this->__('Item was removed successfully.');
                Mage::dispatchEvent('ajax_cart_remove_item_success', array('id' => $id));
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['error'] = $this->__('Can not remove the item.');
            }
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
    /**
     * Minicart ajax update qty action
     */
    public function ajaxUpdateAction()
    {
        if (!$this->_validateFormKey()) {
            Mage::throwException('Invalid form key');
        }
        $id = (int)$this->getRequest()->getParam('id');
        $qty = $this->getRequest()->getParam('qty');
        $result = array();
        if ($id) {
            try {
                $cart = $this->_getCart();
                if (isset($qty)) {
                    $filter = new Zend_Filter_LocalizedToNormalized(
                        array('locale' => Mage::app()->getLocale()->getLocaleCode())
                    );
                    $qty = $filter->filter($qty);
                }
                $quoteItem = $cart->getQuote()->getItemById($id);
                if (!$quoteItem) {
                    Mage::throwException($this->__('Quote item is not found.'));
                }
                if ($qty == 0) {
                    $cart->removeItem($id);
                } else {
                    $quoteItem->setQty($qty)->save();
                }
                $this->_getCart()->save();
                $this->loadLayout();
                $result['content'] = $this->getLayout()->getBlock('minicart_head')->toHtml();
                $result['qty'] = $this->_getCart()->getSummaryQty();
                if (!$quoteItem->getHasError()) {
                    $result['message'] = $this->__('Item was updated successfully.');
                } else {
                    $result['notice'] = $quoteItem->getMessage();
                }
                $result['success'] = 1;
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['error'] = $this->__('Can not save item.');
            }
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
}