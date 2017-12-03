<?php
 
namespace Infobeans\CallForPrice\Setup;
 
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\InstallDataInterface;
 
class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;
 
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }
 
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
 
           $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
         
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'enable_callforprice',
            [
                 'type' => 'int',
                    'label' => 'Enable Call For Price',
                    'input' => 'select',
                    'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                    'required' => false,
                    'sort_order' => 4,
                    'group' => 'General Information',
            ]
        );
 
        $setup->endSetup();
    }
}