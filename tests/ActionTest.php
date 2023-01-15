<?php

namespace Shawnreid\LaravelQuickbooks\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use QuickBooksOnline\API\Data\IPPIntuitEntity;
use QuickBooksOnline\API\Data\IPPInvoice;
use QuickBooksOnline\API\DataService\DataService;
use Shawnreid\LaravelQuickbooks\QuickbooksAction;
use Shawnreid\LaravelQuickbooks\QuickbooksClient;
use QuickBooksOnline\API\Facades\Account;
use QuickBooksOnline\API\Facades\Bill;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Item;
use QuickBooksOnline\API\Facades\Vendor;
use Mockery\MockInterface;
use Mockery;
use QuickBooksOnline\API\Facades\BillPayment;
use QuickBooksOnline\API\Facades\CompanyCurrency;
use QuickBooksOnline\API\Facades\CreditMemo;
use QuickBooksOnline\API\Facades\Department;
use QuickBooksOnline\API\Facades\Deposit;
use QuickBooksOnline\API\Facades\Employee;
use QuickBooksOnline\API\Facades\Estimate;
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
use QuickBooksOnline\API\Facades\VendorCredit;

class ActionTest extends TestCase
{
    use RefreshDatabase;

    private QuickbooksClient $client;
    private QuickbooksAction $action;

    public function setUp(): void
    {
        parent::setup();

        $this->client = new QuickbooksClient();
        $this->action = new QuickbooksAction($this->client->confgiureDataService());
    }

    private function body(): array
    {
        return [
            "Line" => [
                [
                    "Amount" => 100.00,
                    "DetailType" => "SalesItemLineDetail",
                    "SalesItemLineDetail" => [
                    "ItemRef" => [
                        "value" => 20,
                        "name" => "Services"
                    ]
                    ]
                ]
            ],
            "CustomerRef"=> [
                  "value"=> 62
            ],
            "BillEmail" => [
                  "Address" => "Familiystore@intuit.com"
            ],
            "BillEmailCc" => [
                  "Address" => "a@intuit.com"
            ],
            "BillEmailBcc" => [
                  "Address" => "v@intuit.com"
            ]
        ];
    }

    public function test_entity_is_set_to_correct_sdk_facade(): void
    {
        $entities = [
            'invoice'         => Invoice::class,
            'customer'        => Customer::class,
            'vendor'          => Vendor::class,
            'bill'            => Bill::class,
            'billPayment'     => BillPayment::class,
            'account'         => Account::class,
            'item'            => Item::class,
            'estimate'        => Estimate::class,
            'payment'         => Payment::class,
            'journalEntry'    => JournalEntry::class,
            'timeActivity'    => TimeActivity::class,
            'vendorCredit'    => VendorCredit::class,
            'companyCurrency' => CompanyCurrency::class,
            'creditMemo'      => CreditMemo::class,
            'department'      => Department::class,
            'deposit'         => Deposit::class,
            'employee'        => Employee::class,
            'purchase'        => Purchase::class,
            'purchaseOrder'   => PurchaseOrder::class,
            'refundReceipt'   => RefundReceipt::class,
            'salesReceipt'    => SalesReceipt::class,
            'taxAgency'       => TaxAgency::class,
            'taxRate'         => TaxRate::class,
            'taxService'      => TaxService::class,
            'transfer'        => Transfer::class
        ];

        foreach ($entities as $entity => $class) {
            $this->action->{$entity}();
            $this->assertEquals($class, $this->action->getEntity());
        }
    }

    public function test_create_will_throw_error_if_entity_not_specified(): void
    {
        $this->expectError(\Error::class);

        $this->action->create([]);
    }

    public function test_update_will_throw_error_if_entity_not_specified(): void
    {
        $this->expectError(\Error::class);

        $this->action->update(1, []);
    }

    public function test_delete_will_throw_error_if_entity_not_specified(): void
    {
        $this->expectError(\Error::class);

        $this->action->delete(1);
    }

    public function test_find_will_throw_error_if_entity_not_specified(): void
    {
        $this->expectError(\Error::class);

        $this->action->find(1);
    }

    public function test_query_will_return_array_of_results(): void
    {
        $dataServiceMock = $this->partialMock(DataService::class, function (MockInterface $mock) {
            $mock->shouldReceive('Query')
                ->once()
                ->andReturn([]);
        });

        $mock = Mockery::mock(QuickbooksAction::class, [$dataServiceMock])->makePartial();

        $mock->query('SELECT * From Item');
    }

    public function test_find_will_return_object(): void
    {
        $dataServiceMock = $this->partialMock(DataService::class, function (MockInterface $mock) {
            $mock->shouldReceive('findById')
                ->once()
                ->andReturn(new IPPIntuitEntity(['Id' => 1]));
        });

        $mock = Mockery::mock(QuickbooksAction::class, [$dataServiceMock])->makePartial();

        $result = $mock->invoice()->find(1);

        $this->assertInstanceOf(IPPIntuitEntity::class, $result);
    }

    public function test_create_will_return_object(): void
    {
        $dataServiceMock = $this->partialMock(DataService::class, function (MockInterface $mock) {
            $mock->shouldReceive('Add')
                ->once()
                ->andReturn(new IPPIntuitEntity(['Id' => 1]));
        });

        $mock = Mockery::mock(QuickbooksAction::class, [$dataServiceMock])->makePartial();

        $result = $mock->invoice()->create($this->body());

        $this->assertInstanceOf(IPPIntuitEntity::class, $result);
    }

    public function test_update_will_return_object(): void
    {
        $dataServiceMock = $this->partialMock(DataService::class, function (MockInterface $mock) {
            $mock->shouldReceive('findById')
                ->once()
                ->andReturn(new IPPInvoice());

            $mock->shouldReceive('Add')
                ->once()
                ->andReturn(new IPPIntuitEntity(['Id' => 1]));
        });

        $mock = Mockery::mock(QuickbooksAction::class, [$dataServiceMock])->makePartial();


        $result = $mock->invoice()->update(1, $this->body());

        $this->assertInstanceOf(IPPIntuitEntity::class, $result);
    }

    public function test_delete_will_return_object(): void
    {
        $dataServiceMock = $this->partialMock(DataService::class, function (MockInterface $mock) {
            $mock->shouldReceive('findById')
                ->once()
                ->andReturn(new IPPInvoice());

            $mock->shouldReceive('Delete')
                ->once()
                ->andReturn(new IPPIntuitEntity(['Id' => 1]));
        });

        $mock = Mockery::mock(QuickbooksAction::class, [$dataServiceMock])->makePartial();


        $result = $mock->invoice()->delete(1);

        $this->assertInstanceOf(IPPIntuitEntity::class, $result);
    }
}
