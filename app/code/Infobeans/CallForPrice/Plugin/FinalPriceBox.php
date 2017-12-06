<?php
namespace Infobeans\CallForPrice\Plugin;

class FinalPriceBox
{
	protected $helper;

	public function __construct(         
        \Infobeans\CallForPrice\Helper\Data $helper       
    ) {
         $this->helper = $helper;
    }

	function aroundToHtml($subject, callable $proceed)
	{ 
		if($this->helper->isCallForPrice()) {
			return ''; 
		} else{
			return $proceed();
		}
	}
}