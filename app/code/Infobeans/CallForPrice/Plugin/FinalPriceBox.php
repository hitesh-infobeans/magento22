<?php
/**
 * Call For Price Module
 *
 * @category   Infobeans
 * @package    ICC_CallForPrice
 * @version    1.0.0
 *
 */

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
        if ($this->helper->isCallForPrice()) {
            return '';
	} else {
            return $proceed();
	}
    }
}