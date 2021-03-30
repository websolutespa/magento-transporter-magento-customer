<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterMagentoCustomer\Model\Attribute;

use Magento\Customer\Model\AddressFactory;
use Magento\Customer\Model\ResourceModel\Address;

class GetCustomerAddressById
{
    /**
     * @var Address
     */
    private $addressResource;

    /**
     * @var AddressFactory
     */
    private $addressFactory;

    /**
     * @param Address $addressResource
     * @param AddressFactory $addressFactory
     */
    public function __construct(
        Address $addressResource,
        AddressFactory $addressFactory
    ) {
        $this->addressResource = $addressResource;
        $this->addressFactory = $addressFactory;
    }

    /**
     * @param int $addressId
     * @return \Magento\Customer\Model\Address
     */
    public function execute(int $addressId): \Magento\Customer\Model\Address
    {
        $address = $this->addressFactory->create();
        $this->addressResource->load($address, $addressId);
        return $address;
    }
}
