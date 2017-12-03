<?php
    
namespace Infobeans\CallForPrice\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{   
    
    const CONFIG_MODULE_ENABLE = 'callforprice/general/enable';
    
    const CONFIG_BUTTON_TITLE = 'callforprice/general/button_title';  

    const XML_PATH_ADMIN_EMAIL ='callforprice/general/email_to';

    const XML_PATH_EMAIL_IDENTITY = 'callforprice/general/identity';

    const XML_PATH_SUCCESS_MESSAGE = 'callforprice/general/callforprice_success_message';

    protected $scopeConfig;
    
    protected $storeManager;
    
    protected $_transportBuilder;
    
    protected $inlineTranslation;
    
    protected $date;

    
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    ) {
        parent::__construct($context);
        $this->scopeConfig = $context->getScopeConfig();
        $this->storeManager = $storeManager;
        $this->_transportBuilder=$transportBuilder;
        $this->inlineTranslation=$inlineTranslation;
        $this->date = $date;
    }
    
    public function isModuleEnable(){
            
       return $this->scopeConfig->getValue(self::CONFIG_MODULE_ENABLE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE); 
    }
    
    public function getButtonTitle(){
            
       return $this->scopeConfig->getValue(self::CONFIG_BUTTON_TITLE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE); 
    }

    public function getSuccessMessage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SUCCESS_MESSAGE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

        /**
     * Retrieve Admin email
     */
    public function getAdminEmail()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ADMIN_EMAIL,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

        /**
     * Return email identity
     *
     * @return mixed
     */
    public function getEmailIdentity()
    { 
        return $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL_IDENTITY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Send Order cancel email notification to admin
     */
    public function sendEmail($params, $options)
    {

        $templateOptions =  [
                              'area' => $options['area'],
                              'store' => $this->storeManager->getStore()->getId()
                            ];
                             
        $templateVars = [
                            'store' => $this->storeManager->getStore(),
                            'name'=>$params['name'],
                             'email' =>$params['email'],
                             'phone' =>$params['phone'],
                             'comment' =>$params['comment'],
                            'product' => $params['product'] 
                        ];
           
        $this->inlineTranslation->suspend();
        
         $to = [$this->getAdminEmail()];
       
        $transport = $this->_transportBuilder->setTemplateIdentifier($options['emailTemplate'])
                        ->setTemplateOptions($templateOptions)
                        ->setTemplateVars($templateVars)
                        ->setFrom($this->getEmailIdentity())
                        ->addTo($to)
                        ->getTransport();
        $transport->sendMessage();
        $this->inlineTranslation->resume();
    }
    
    
    
}    
    
    
?>