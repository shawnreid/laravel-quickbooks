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
    public function __construct(
        private DataService $dataService,
        private string $entity = ''
    ) {
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
     * Quickbooks invoice entity
     *
     * @return self
     */
    public function invoice(): self
    {
        $this->entity = Invoice::class;

        return $this;
    }

    /**
     * Quickbooks customer entity
     *
     * @return self
     */
    public function customer(): self
    {
        $this->entity = Customer::class;

        return $this;
    }

    /**
     * Quickbooks vendor entity
     *
     * @return self
     */
    public function vendor(): self
    {
        $this->entity = Vendor::class;

        return $this;
    }

    /**
     * Quickbooks bill entity
     *
     * @return self
     */
    public function bill(): self
    {
        $this->entity = Bill::class;

        return $this;
    }

    /**
     * Quickbooks bill payment entity
     *
     * @return self
     */
    public function billPayment(): self
    {
        $this->entity = BillPayment::class;

        return $this;
    }

    /**
     * Quickbooks account entity
     *
     * @return self
     */
    public function account(): self
    {
        $this->entity = Account::class;

        return $this;
    }

    /**
     * Quickbooks item entity
     *
     * @return self
     */
    public function item(): self
    {
        $this->entity = Item::class;

        return $this;
    }

    /**
     * Quickbooks estimate entity
     *
     * @return self
     */
    public function estimate(): self
    {
        $this->entity = Estimate::class;

        return $this;
    }

    /**
     * Quickbooks payment entity
     *
     * @return self
     */
    public function payment(): self
    {
        $this->entity = Payment::class;

        return $this;
    }

    /**
     * Quickbooks journal entry entity
     *
     * @return self
     */
    public function journalEntry(): self
    {
        $this->entity = JournalEntry::class;

        return $this;
    }

    /**
     * Quickbooks time activity entity
     *
     * @return self
     */
    public function timeActivity(): self
    {
        $this->entity = TimeActivity::class;

        return $this;
    }

    /**
     * Quickbooks vendor credit entity
     *
     * @return self
     */
    public function vendorCredit(): self
    {
        $this->entity = VendorCredit::class;

        return $this;
    }

    /**
     * Quickbooks company currency entity
     *
     * @return self
     */
    public function companyCurrency(): self
    {
        $this->entity = CompanyCurrency::class;

        return $this;
    }

    /**
     * Quickbooks credit memo entity
     *
     * @return self
     */
    public function creditMemo(): self
    {
        $this->entity = CreditMemo::class;

        return $this;
    }

    /**
     * Quickbooks department entity
     *
     * @return self
     */
    public function department(): self
    {
        $this->entity = Department::class;

        return $this;
    }

    /**
     * Quickbooks deposit entity
     *
     * @return self
     */
    public function deposit(): self
    {
        $this->entity = Deposit::class;

        return $this;
    }

    /**
     * Quickbooks employee entity
     *
     * @return self
     */
    public function employee(): self
    {
        $this->entity = Employee::class;

        return $this;
    }

    /**
     * Quickbooks purchase entity
     *
     * @return self
     */
    public function purchase(): self
    {
        $this->entity = Purchase::class;

        return $this;
    }

    /**
     * Quickbooks purchase order entity
     *
     * @return self
     */
    public function purchaseOrder(): self
    {
        $this->entity = PurchaseOrder::class;

        return $this;
    }

    /**
     * Quickbooks refund receipt entity
     *
     * @return self
     */
    public function refundReceipt(): self
    {
        $this->entity = RefundReceipt::class;

        return $this;
    }

    /**
     * Quickbooks sales receipt entity
     *
     * @return self
     */
    public function salesReceipt(): self
    {
        $this->entity = SalesReceipt::class;

        return $this;
    }

    /**
     * Quickbooks tax agency entity
     *
     * @return self
     */
    public function taxAgency(): self
    {
        $this->entity = TaxAgency::class;

        return $this;
    }

    /**
     * Quickbooks tax rate entity
     *
     * @return self
     */
    public function taxRate(): self
    {
        $this->entity = TaxRate::class;

        return $this;
    }

    /**
     * Quickbooks tax service entity
     *
     * @return self
     */
    public function taxService(): self
    {
        $this->entity = TaxService::class;

        return $this;
    }

    /**
     * Quickbooks transfer entity
     *
     * @return self
     */
    public function transfer(): self
    {
        $this->entity = Transfer::class;

        return $this;
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
    public function find(int $id)
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
            throw new \Exception('No entity type specified.');
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
