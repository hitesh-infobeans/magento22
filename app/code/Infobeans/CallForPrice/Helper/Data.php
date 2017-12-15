<?php
/**
 * Call For Price Module
 *
 * @category   Infobeans
 * @package    ICC_CallForPrice
 * @version    1.0.0
 *
 */

namespace Infobeans\CallForPrice\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const CONFIG_MODULE_ENABLE = 'callforprice/general/enable';
    
    const CONFIG_BUTTON_TITLE = 'callforprice/general/button_title';

    const XML_PATH_ADMIN_EMAIL ='callforprice/general/email_to';
    
    const XML_PATH_FRONTEND_EMAIL_TEMPLATE ='callforprice/general/frontend_callforprice_template';

    const XML_PATH_EMAIL_IDENTITY = 'callforprice/general/identity';

    const XML_PATH_SUCCESS_MESSAGE = 'callforprice/general/callforprice_success_message';

    protected $scopeConfig;
    
    protected $storeManager;
    
    protected $_transportBuilder;
    
    protected $inlineTranslation;
    
    protected $date;
    
    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context,
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager,
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     */
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
    
    /**
     * Check Is Module is Enable or not
     *
     * @return type booolean
     */
    public function isModuleEnable()
    {
       return $this->scopeConfig->getValue(self::CONFIG_MODULE_ENABLE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    
    /**
     * Get Button Title
     *
     * @return type string
     */
    public function getButtonTitle()
    {
       return $this->scopeConfig->getValue(self::CONFIG_BUTTON_TITLE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    
    /**
     * Get Success Message
     *
     * @return type string
     */
    public function getSuccessMessage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SUCCESS_MESSAGE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Get Frontend Email Template
     *
     * @return type string
     */
    public function getFrontendEmailTemplate()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_FRONTEND_EMAIL_TEMPLATE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Check Call For Price Module is Enable or not
     *
     * @return type boolean
     */
    public function isCallForPrice() 
    {
        if (!$this->isModuleEnable()) {
            return false;
        }
        return true;
    }

    /**
     * Get Admin Email Id
     *
     * @return type string
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
     * Send Email
     * @params $toEmail,$params,$options
     */
    public function sendEmail($toEmail,$params, $options)
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
                            'qty' =>$params['qty'],
                            'comment' =>$params['comment'],
                            'product' => $params['product']
                        ];
           
        $this->inlineTranslation->suspend();
        
        $to = [$toEmail];
       
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
