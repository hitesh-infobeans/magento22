<?php
namespace Infobeans\CallForPrice\Block\Product\View;

class CallForPrice extends \Magento\Framework\View\Element\Template
{      
    public $helper;  
    
    protected $_coreRegistry = null;    
    
    public $product = null;
    
    public function __construct(                    
        \Magento\Framework\View\Element\Template\Context $context,
        \Infobeans\CallForPrice\Helper\Data $helper,
        \Magento\Framework\Registry $registry
    )
    {    
        $this->helper = $helper;    
        $this->_coreRegistry = $registry;   
        parent::__construct($context);
    }

    public function isCallForPrice()
    {       
        if(!$this->helper->isModuleEnable())
        {
            return false;
        }
        return true;
    }
    
    public function getButtonTitle()
    {
        if($buttonTitle = $this->helper->getButtonTitle())
        {
            return $buttonTitle;
        }  
    }
    
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
    
    
}
