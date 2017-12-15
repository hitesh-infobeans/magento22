<?php
/**
 * Call For Price Module
 *
 * @category   Infobeans
 * @package    ICC_CallForPrice
 * @version    1.0.0
 *
 */

namespace Infobeans\CallForPrice\Controller\Index;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\InputException;

class Post extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    
    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    private $productRepository;
    
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;
    
    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;
    
    /**
     * @var \Infobeans\Ordercancel\Helper\Data
     */
    protected $helper;
    
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    
    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\Escaper $escaper
     * @param \Infobeans\CallForPrice\Helper\Data $helper
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Escaper $escaper,
        \Infobeans\CallForPrice\Helper\Data $helper,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->productRepository = $productRepository;                                                     
        $this->escaper = $escaper;
        $this->helper = $helper;
        $this->logger = $logger;
        parent::__construct($context);
    }
    
    /**
     *
     * @return Order Object
     */
    protected function _initProduct()
    {
         $id = $this->getRequest()->getPost('product_id');
          
        try {
            $product = $this->productRepository->getById($id);
           
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addError(__('This product no longer exists.'));
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
            return false;
        } catch (InputException $e) {
            $this->messageManager->addError(__('This product no longer exists.'));
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
            return false;
        }
        return $product;
    } 
    
    /**
     *  Validate Form
     * @params $post
     * @return type boolean
     */
    protected function validateForm($post)
    {
        if (!\Zend_Validate::is(trim($post['product_id']), 'NotEmpty')) {
            return false;
        }
       
        if (!\Zend_Validate::is(trim($post['name']), 'NotEmpty')) {
            return false;
        }
        
        if (!\Zend_Validate::is(trim($post['email']), 'NotEmpty')) {
            return false;
        }
        
        if (!\Zend_Validate::is(trim($post['phone']), 'NotEmpty')) {
            return false;
        }

        if (!\Zend_Validate::is(trim($post['qty']), 'NotEmpty')) {
            return false;
        }
        
        if (!\Zend_Validate::is(trim($post['comment']), 'NotEmpty')) {
            return false;
        }

        return true;
    }
    
    /**
     * Execute Function
     * 
     */
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();
        
        $resultRedirect = $this->resultRedirectFactory->create();
         
        $redirectUrl = $this->_redirect->getRefererUrl();

        if (!$this->helper->isModuleEnable()) {
            return $resultRedirect->setPath($redirectUrl);
        }
        
        if ($post) {
            try {
                $product = $this->_initProduct();

                if (!$this->validateForm($post)) {
                    throw new \Magento\Framework\Exception\LocalizedException();
                }
                
                $name = $this->escaper->escapeHtml(trim($post['name']));
                $email = $this->escaper->escapeHtml(trim($post['email']));
                $phone = $this->escaper->escapeHtml(trim($post['phone']));
                $qty = $this->escaper->escapeHtml(trim($post['qty']));
                $comment = $this->escaper->escapeHtml(trim($post['comment']));
                
                $message=$this->helper->getSuccessMessage();
                
                $params['product'] = $product;
                $params['name'] = $name;
                $params['email'] =$email;
                $params['phone'] = $phone;
                $params['qty'] = $qty;
                $params['comment'] =$comment;

                $options['emailTemplate'] = "admin_callforprice_template" ;
                $options['area'] =\Magento\Framework\App\Area::AREA_ADMINHTML;
              
                  // Send Email to Admin
                $to = $this->helper->getAdminEmail();
                $this->helper->sendEmail($to,$params, $options);

                // Send Email to Customer
                $options['emailTemplate'] =$this->helper->getFrontendEmailTemplate();
                $options['area'] =\Magento\Framework\App\Area::AREA_FRONTEND;
                $to = $email;
                $this->helper->sendEmail($to,$params, $options);

                $this->messageManager->addSuccess(__($message));
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(__('Something is Wrong. Please try again'));
                $this->logger->critical($e);
            }
            return $resultRedirect->setPath($redirectUrl);
        }
        return $resultRedirect->setPath($redirectUrl);
    }
}
