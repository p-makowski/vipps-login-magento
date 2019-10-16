<?php
/**
 * Copyright 2019 Vipps
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 *  documentation files (the "Software"), to deal in the Software without restriction, including without limitation
 *  the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software,
 *  and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED
 *  TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL
 *  THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 *  CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 *  IN THE SOFTWARE.
 */

namespace Vipps\Login\Api;

use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Vipps\Login\Api\Data\UserInfoInterface;
use Vipps\Login\Api\Data\VippsCustomerAddressInterface;
use Vipps\Login\Api\Data\VippsCustomerInterface;

/**
 * Interface VippsAddressManagementInterface
 * @package Vipps\Login\Api
 * @api
 */
interface VippsAddressManagementInterface
{
    /**
     * @param UserInfoInterface $userInfo
     * @param VippsCustomerInterface $vippsCustomer
     *
     * @return VippsCustomerAddressInterface[]|[]
     */
    public function fetchAddresses(UserInfoInterface $userInfo, VippsCustomerInterface $vippsCustomer);

    /**
     * @param UserInfoInterface $userInfo
     * @param VippsCustomerInterface $vippsCustomer
     * @param CustomerInterface $customer
     *
     * @return mixed
     */
    public function apply(
        UserInfoInterface $userInfo,
        VippsCustomerInterface $vippsCustomer,
        CustomerInterface $customer
    );

    /**
     * @param VippsCustomerAddressInterface $vippsAddress
     * @param AddressInterface[] $magentoAddresses
     *
     * @return bool
     */
    public function assign(VippsCustomerAddressInterface $vippsAddress, array $magentoAddresses);
}
