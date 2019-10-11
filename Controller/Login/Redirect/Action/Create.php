<?php
/**
 * Copyright 2019 Vipps
 *
 *    Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 *    documentation files (the "Software"), to deal in the Software without restriction, including without limitation
 *    the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software,
 *    and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 *    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED
 *    TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL
 *    THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 *    CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 *    IN THE SOFTWARE
 */

declare(strict_types=1);

namespace Vipps\Login\Controller\Login\Redirect\Action;

use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\State\InputMismatchException;
use Magento\Framework\Exception\AuthorizationException;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Customer\Model\CustomerRegistry;
use Vipps\Login\Api\VippsAccountManagementInterface;
use Vipps\Login\Api\VippsAddressManagementInterface;
use Vipps\Login\Gateway\Command\UserInfoCommand;
use Vipps\Login\Model\Customer\Creator;
use Vipps\Login\Model\VippsAddressManagement;

/**
 * Class Create
 * @package Vipps\Login\Controller\Login\Redirect\Action
 */
class Create implements ActionInterface
{
    /**
     * @var RedirectFactory
     */
    private $redirectFactory;

    /**
     * @var SessionManagerInterface|Session
     */
    private $sessionManager;

    /**
     * @var CustomerRegistry
     */
    private $customerRegistry;

    /**
     * @var UserInfoCommand
     */
    private $userInfoCommand;

    /**
     * @var Creator
     */
    private $creator;

    /**
     * @var VippsAccountManagementInterface
     */
    private $vippsAccountManagement;

    /**
     * @var VippsAccountManagementInterface
     */
    private $vippsAddressManagement;

    /**
     * Create constructor.
     *
     * @param RedirectFactory $redirectFactory
     * @param SessionManagerInterface $sessionManager
     * @param CustomerRegistry $customerRegistry
     * @param UserInfoCommand $userInfoCommand
     * @param Creator $creator
     * @param VippsAccountManagementInterface $vippsAccountManagement
     * @param VippsAddressManagementInterface $vippsAddressManagement
     */
    public function __construct(
        RedirectFactory $redirectFactory,
        SessionManagerInterface $sessionManager,
        CustomerRegistry $customerRegistry,
        UserInfoCommand $userInfoCommand,
        Creator $creator,
        VippsAccountManagementInterface $vippsAccountManagement,
        VippsAddressManagementInterface $vippsAddressManagement
    ) {
        $this->redirectFactory = $redirectFactory;
        $this->sessionManager = $sessionManager;
        $this->customerRegistry = $customerRegistry;
        $this->userInfoCommand = $userInfoCommand;
        $this->creator = $creator;
        $this->vippsAccountManagement = $vippsAccountManagement;
        $this->vippsAddressManagement = $vippsAddressManagement;
    }

    /**
     * @param $token
     *
     * @return bool|Redirect
     * @throws InputException
     * @throws InputMismatchException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     * @throws \Exception
     */
    public function execute($token)
    {
        if ($this->canCreate($token)) {

            $userInfo = $this->userInfoCommand->execute($token['access_token']);

            $magentoCustomer = $this->creator->create($userInfo);
            $vippsCustomer = $this->vippsAccountManagement->link($userInfo, $magentoCustomer);

            $customer = $this->customerRegistry->retrieveByEmail($magentoCustomer->getEmail());
            $this->sessionManager->setCustomerAsLoggedIn($customer);

            $this->vippsAddressManagement->apply($userInfo, $vippsCustomer, $customer->getDataModel());

            $redirect = $this->redirectFactory->create();
            $redirect->setPath('customer/account');
            return $redirect;
        }

        return false;
    }

    /**
     * @param $token
     *
     * @return bool
     */
    private function canCreate($token)
    {
        return true;
    }
}
