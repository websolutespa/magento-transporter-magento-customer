<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterMagentoCustomer\Model;

use Exception;
use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\Customer as CustomerResource;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Silicon\CustomerGroup\Model\ResourceModel\IsCustomerGroupById;
use Silicon\ImporterPrice\Model\GetCustomerByCodCli;
use Websolute\TransporterBase\Model\SetAreaCode;

class SetCustomerGroup
{
    /**
     * @var SetAreaCode
     */
    private $setAreaCode;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var CustomerResource
     */
    private $customerResource;

    /**
     * @var GetCustomerByCodCli
     */
    private $getCustomerByCodCli;

    /**
     * @var IsCustomerGroupById
     */
    private $isCustomerGroupById;

    /**
     * @param GetCustomerByCodCli $getCustomerByCodCli
     * @param IsCustomerGroupById $isCustomerGroupById
     * @param CustomerResource $customerResource
     * @param SetAreaCode $setAreaCode
     * @param ProductRepository $productRepository
     */
    public function __construct(
        GetCustomerByCodCli $getCustomerByCodCli,
        IsCustomerGroupById $isCustomerGroupById,
        CustomerResource $customerResource,
        SetAreaCode $setAreaCode,
        ProductRepository $productRepository
    ) {
        $this->setAreaCode = $setAreaCode;
        $this->productRepository = $productRepository;
        $this->customerResource = $customerResource;
        $this->getCustomerByCodCli = $getCustomerByCodCli;
        $this->isCustomerGroupById = $isCustomerGroupById;
    }

    /**
     * @param int $customerGroupId
     * @param string $codCli
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function execute(int $customerGroupId, string $codCli)
    {
        /** @var Customer $customer */
        $customer = $this->getCustomerByCodCli->execute($codCli);

        $customerGroupId = $this->isCustomerGroupById->execute($customerGroupId);

        if ($customer && $customerGroupId !== false) {
            $customer->setGroupId($customerGroupId);

            try {
                $this->customerResource->save($customer);
            } catch (Exception $e) {
                throw new LocalizedException(__('Customer group error on save, error message: %1', $e->getMessage()));
            }
        }
    }
}
