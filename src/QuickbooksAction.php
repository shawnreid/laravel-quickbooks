<?php

declare(strict_types=1);

namespace Shawnreid\LaravelQuickbooks;

use QuickBooksOnline\API\Data\IPPIntuitEntity;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Account;
use QuickBooksOnline\API\Facades\Bill;
use QuickBooksOnline\API\Facades\BillPayment;
use QuickBooksOnline\API\Facades\CompanyCurrency;
use QuickBooksOnline\API\Facades\CreditMemo;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\Department;
use QuickBooksOnline\API\Facades\Deposit;
use QuickBooksOnline\API\Facades\Employee;
use QuickBooksOnline\API\Facades\Estimate;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Item;
use QuickBooksOnline\API\Facades\JournalEntry;
use QuickBooksOnline\API\Facades\Payment;
use QuickBooksOnline\API\Facades\Purchase;
use QuickBooksOnline\API\Facades\PurchaseOrder;
use QuickBooksOnline\API\Facades\RefundReceipt;
use QuickBooksOnline\API\Facades\SalesReceipt;
use QuickBooksOnline\API\Facades\TaxAgency;
use QuickBooksOnline\API\Facades\TaxRate;
use QuickBooksOnline\API\Facades\TaxService;
use QuickBooksOnline\API\Facades\TimeActivity;
use QuickBooksOnline\API\Facades\Transfer;
use QuickBooksOnline\API\Facades\Vendor;
use QuickBooksOnline\API\Facades\VendorCredit;

class QuickbooksAction
{
    private array $entities = [
        'invoice'         => Invoice::class,
        'customer'        => Customer::class,
        'vendor'          => Vendor::class,
        'bill'            => Bill::class,
        'billpayment'     => BillPayment::class,
        'account'         => Account::class,
        'item'            => Item::class,
        'estimate'        => Estimate::class,
        'payment'         => Payment::class,
        'journalentry'    => JournalEntry::class,
        'timeactivity'    => TimeActivity::class,
        'vendorcredit'    => VendorCredit::class,
        'companycurrency' => CompanyCurrency::class,
        'creditmemo'      => CreditMemo::class,
        'department'      => Department::class,
        'deposit'         => Deposit::class,
        'employee'        => Employee::class,
        'purchase'        => Purchase::class,
        'purchaseorder'   => PurchaseOrder::class,
        'refundreceipt'   => RefundReceipt::class,
        'salesreceipt'    => SalesReceipt::class,
        'taxagency'       => TaxAgency::class,
        'taxrate'         => TaxRate::class,
        'taxservice'      => TaxService::class,
        'transfer'        => Transfer::class,
    ];
    private string $entity = '';

    public function __construct(private DataService $dataService)
    {
    }

    /**
     * Set entity type
     *
     * @param string $function
     * @param array $arguments
     * @return self
     */
    public function __call(string $function, array $arguments): self
    {
        $key = strtolower($function);

        if (isset($this->entities[$key])) {
            $this->entity = $this->entities[$key];
            return $this;
        }

        throw new \Error(sprintf("Call to undefined method %s::%s", __CLASS__, $function));
    }

    /**
     * Quickbooks SQL query
     *
     * @param string $query
     * @return array
     */
    public function query(string $query): array
    {
        return $this->dataService->Query($query);
    }

    /**
     * Create entity
     *
     * @param array $body
     * @return IPPIntuitEntity
     */
    public function create(array $body): IPPIntuitEntity
    {
        $this->verifyEntityTypeSpecified();

        return $this->dataService->Add(
            $this->entity::create($body)
        );
    }

    /**
     * Update entity
     *
     * @param int $id
     * @param array $body
     * @return IPPIntuitEntity
     */
    public function update(int $id, array $body): IPPIntuitEntity
    {
        $this->verifyEntityTypeSpecified();

        return $this->dataService->Add(
            $this->entity::update($this->find($id), $body)
        );
    }

    /**
     * Delete entity
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        $this->verifyEntityTypeSpecified();

        return $this->dataService->Delete($this->find($id));
    }

    /**
     * Find entity
     *
     * @param int $id
     * @return IPPIntuitEntity
     */
    public function find(int $id): IPPIntuitEntity
    {
        $this->verifyEntityTypeSpecified();

        return $this->dataService->findById($this->getClassName(), $id);
    }

    /**
     * Throw error if entity type not specified
     *
     * @return void
     */
    private function verifyEntityTypeSpecified(): void
    {
        if (empty($this->entity)) {
            throw new \Error('No entity type specified.');
        }
    }

    /**
     * Get class name of specified entitys
     *
     * @return string
     */
    public function getClassName(): string
    {
        return substr((string) strrchr($this->entity, '\\'), 1);
    }

    /**
     * Return selected entity
     *
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }
}
