<?php
namespace Infobeans\CallForPrice\Plugin;

class FinalPriceBox
{
	public function afterGetName(\Magento\Catalog\Model\Product $subject, $result) {
            return "Apple ".$result; // Adding Apple in product name
    }
}