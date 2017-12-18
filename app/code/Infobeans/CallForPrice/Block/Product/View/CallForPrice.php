<?php
/**
 * Call For Price Module
 *
 * @category   Infobeans
 * @package    ICC_CallForPrice
 * @version    1.0.0
 *
 */

namespace Infobeans\CallForPrice\Block\Product\View;

class CallForPrice extends \Magento\Framework\View\Element\Template
{
    public $helper;
    
    protected $_coreRegistry = null;
    
    public $product = null;
    
    /**
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Infobeans\CallForPrice\Helper\Data $helper
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Infobeans\CallForPrice\Helper\Data $helper,
        \Magento\Framework\Registry $registry
    ) {
        $this->helper = $helper;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }
    
    /**
     * Check Is Call For Price is Enable or not
     *
     * @return type booolean
     */
    public function isCallForPrice()
    {
        if (!$this->helper->isModuleEnable()) {
            return false;
        }
        return true;
    }
    
    /**
     * Get Button Title
     *
     * @return type string
     */
    public function getButtonTitle()
    {
        if ($buttonTitle = $this->helper->getButtonTitle()) {
            return $buttonTitle;
        }
    }
    
    /**
     * Get Submit Url
     *
     * @return type string
     */
    public function getSubmitUrl()
    {
         return $this->getUrl('callforprice/index/post');
    }
    
    /**
     * @return Product
     */
    public function getProduct()
    {
        if (!$this->product) {
            $this->product = $this->_coreRegistry->registry('product');
        }
        return $this->product;
    }
    
    /**
     * Set Product
     *
     * @return type void
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }
}
