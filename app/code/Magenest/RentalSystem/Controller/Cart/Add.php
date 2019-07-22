<?php
/**
 * Created by PhpStorm.
 * User: ducanh
 * Date: 28/01/2019
 * Time: 16:11
 */

namespace Magenest\RentalSystem\Controller\Cart;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;

class Add extends \Magento\Checkout\Controller\Cart\Add
{
    const XML_PATH_POLICY_REQUIRED = 'rental_system/policy/required';
    const XML_PATH_POLICY_ERROR    = 'rental_system/policy/errormsg';

    /**
     * Core registry
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;

    /**
     * Add constructor.
     *
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param Session $checkoutSession
     * @param StoreManagerInterface $storeManager
     * @param Validator $formKeyValidator
     * @param CustomerCart $cart
     * @param ProductRepositoryInterface $productRepository
     * @param Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        Session $checkoutSession,
        StoreManagerInterface $storeManager,
        Validator $formKeyValidator,
        CustomerCart $cart,
        ProductRepositoryInterface $productRepository,
        Registry $coreRegistry
    ) {
        $this->_messageManager = $context->getMessageManager();
        $this->_coreRegistry   = $coreRegistry;
        parent::__construct(
            $context,
            $scopeConfig,
            $checkoutSession,
            $storeManager,
            $formKeyValidator,
            $cart,
            $productRepository
        );
    }

    /**
     * Add product to shopping cart action
     * @return \Magento\Framework\Controller\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {
        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        $params             = $this->getRequest()->getParams();
        $productInformation = $this->_initProduct();
        if ($productInformation->getTypeId() === 'rental') {
            $this->productRepository->cleanCache();
            $this->cart->getQuote()->setTotalsCollectedFlag(false);
            foreach ($this->cart->getQuote()->getAllAddresses() as $address) {
                $address->unsetData('cached_items_all');
            }
            $rentalProductParams = $params;
            try {
                if (isset($rentalProductParams['qty'])) {
                    $filter                     = new \Zend_Filter_LocalizedToNormalized(
                        [
                            'locale' => $this->_objectManager->get(
                                \Magento\Framework\Locale\ResolverInterface::class
                            )->getLocale()
                        ]
                    );
                    $rentalProductParams['qty'] = $filter->filter($rentalProductParams['qty']);
                }

                $product = clone $this->_initProduct();
                if (empty($rentalProductParams['additional_options'])) {
                    $this->messageManager->addNoticeMessage(__('Please select rent duration!'));
                    if ($this->_scopeConfig->getValue(self::XML_PATH_POLICY_REQUIRED) == 1) {
                        $errorMsg = $this->_scopeConfig->getValue(self::XML_PATH_POLICY_ERROR);
                        $this->messageManager->addNoticeMessage($errorMsg);
                    }

                    return $this->goBack($product->getUrlInStore());
                }
                if (!empty($customerOptions)) {
                    $product->addCustomOption('additional_options', $customerOptions);
                }
                $related = $this->getRequest()->getParam('related_product');

                /**
                 * Check product availability
                 */
                if (!$product) {
                    return $this->goBack();
                }

                $this->cart->addProduct($product, $rentalProductParams);
                if (!empty($related)) {
                    $this->cart->addProductsByIds($related);
                }

                $this->_eventManager->dispatch(
                    'checkout_cart_add_product_complete',
                    ['product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse()]
                );

                if (!$this->_checkoutSession->getNoCartRedirect(true)) {
                    if (!$this->cart->getQuote()->getHasError()) {
                        $message = __(
                            'You added %1 to your shopping cart.',
                            $product->getName()
                        );
                        $this->messageManager->addSuccessMessage($message);
                    }
                }
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                if ($this->_checkoutSession->getUseNotice(true)) {
                    $this->messageManager->addNotice(
                        $this->_objectManager->get(\Magento\Framework\Escaper::class)->escapeHtml($e->getMessage())
                    );
                } else {
                    $messages = array_unique(explode("\n", $e->getMessage()));
                    foreach ($messages as $message) {
                        $this->messageManager->addError(
                            $this->_objectManager->get(\Magento\Framework\Escaper::class)->escapeHtml($message)
                        );
                    }
                }

                $url = $this->_checkoutSession->getRedirectUrl(true);

                if (!$url) {
                    $cartUrl = $this->_objectManager->get(\Magento\Checkout\Helper\Cart::class)->getCartUrl();
                    $url     = $this->_redirect->getRedirectUrl($cartUrl);
                }

                return $this->goBack($url);
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('We can\'t add this item to your shopping cart right now.'));
                $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);

                return $this->goBack();
            }


            $this->cart->save();
            if (isset($product)) {
                return $this->goBack(null, $product);
            } else {
                return $this->goBack(null, $this->_initProduct());
            }
        } else {
            try {
                if (isset($params['qty'])) {
                    $filter        = new \Zend_Filter_LocalizedToNormalized(
                        [
                            'locale' => $this->_objectManager->get(
                                \Magento\Framework\Locale\ResolverInterface::class
                            )->getLocale()
                        ]
                    );
                    $params['qty'] = $filter->filter($params['qty']);
                }

                $product = $this->_initProduct();
                $related = $this->getRequest()->getParam('related_product');

                /**
                 * Check product availability
                 */
                if (!$product) {
                    return $this->goBack();
                }

                $this->cart->addProduct($product, $params);
                if (!empty($related)) {
                    $this->cart->addProductsByIds(explode(',', $related));
                }

                $this->cart->save();

                /**
                 * @todo remove wishlist observer \Magento\Wishlist\Observer\AddToCart
                 */
                $this->_eventManager->dispatch(
                    'checkout_cart_add_product_complete',
                    ['product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse()]
                );

                if (!$this->_checkoutSession->getNoCartRedirect(true)) {
                    if (!$this->cart->getQuote()->getHasError()) {
                        $message = __(
                            'You added %1 to your shopping cart.',
                            $product->getName()
                        );
                        $this->messageManager->addSuccessMessage($message);
                    }

                    return $this->goBack(null, $product);
                }
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                if ($this->_checkoutSession->getUseNotice(true)) {
                    $this->messageManager->addNotice(
                        $this->_objectManager->get(\Magento\Framework\Escaper::class)->escapeHtml($e->getMessage())
                    );
                } else {
                    $messages = array_unique(explode("\n", $e->getMessage()));
                    foreach ($messages as $message) {
                        $this->messageManager->addError(
                            $this->_objectManager->get(\Magento\Framework\Escaper::class)->escapeHtml($message)
                        );
                    }
                }

                $url = $this->_checkoutSession->getRedirectUrl(true);

                if (!$url) {
                    $cartUrl = $this->_objectManager->get(\Magento\Checkout\Helper\Cart::class)->getCartUrl();
                    $url     = $this->_redirect->getRedirectUrl($cartUrl);
                }

                return $this->goBack($url);
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('We can\'t add this item to your shopping cart right now.'));
                $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);

                return $this->goBack();
            }
        }
    }
}
