<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_UvDeskConnector
 * @author    Webkul Software Private Limited
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\UvDeskConnector\Controller\Adminhtml\Tickets;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class GetTickets extends \Magento\Backend\App\Action
{
    /** @var \Magento\Framework\View\Result\PageFactory */    
    protected $_resultPageFactory;

    /** @var \Magento\Framework\Json\Helper\Data */    
    protected $_jsonHelper;

    /** @var \UvDeskConnector\Model\TicketManager */    
    protected $_ticketManager;

    /** @var \Webkul\UvDeskConnector\Helper\Tickets */    
    protected $_ticketsHelper;

    /**
     * @param \Magento\Backend\App\Action\Context         $context
     * @param \Magento\Framework\View\Result\PageFactory  $resultPageFactory
     * @param \Magento\Framework\Json\Helper\Data         $jsonHelper
     * @param \Webkul\UvDeskConnector\Model\TicketManager $ticketManager
     * @param \Webkul\UvDeskConnector\Helper\Tickets      $ticketsHelper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,        
        \Webkul\UvDeskConnector\Model\TicketManager $ticketManager,
        \Webkul\UvDeskConnector\Helper\Tickets $ticketsHelper
    ) 
    {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_json = $jsonHelper;
        $this->_ticketManager = $ticketManager;
        $this->_ticketsHelper = $ticketsHelper;
    }

    public function execute()
    {
        $page = $this->checkStatus('pageNo');
        $label = $this->checkStatus('labels');  
        $tab = $this->checkStatus('tab');
        $agent = $this->checkStatus('agent');
        $customer = $this->checkStatus('customer');
        $group = $this->checkStatus('group');
        $team = $this->checkStatus('team');
        $priority = $this->checkStatus('priority');
        $type = $this->checkStatus('type');
        $tag = $this->checkStatus('tag');
        $mailbox =$this->checkStatus('mailbox');
        $tickets = $this->_ticketManager->getAllTicketss($page, $label, $tab, $agent, $customer, $group, $team, $priority, $type, $tag, $mailbox);
        $formatedTickets = $this->_ticketsHelper->formatData($tickets);
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($this->_json->jsonEncode($formatedTickets));          
        // $resultPage = $this->_resultPageFactory->create();
        // $resultPage->getConfig()->getTitle()->prepend(__('Tickets'));
        // return $resultPage;
    }

    public function checkStatus($code)
    {
        $flag = $this->getRequest()->getParam($code);
        if (isset($flag)) {
            return $this->getRequest()->getParam($code);
        } else {
            return null;
        }

    }
    

    /*
     * Check permission via ACL resource
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_UvDeskConnector::tickets_index');
    }
}
