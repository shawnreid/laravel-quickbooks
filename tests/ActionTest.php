<?php

namespace Shawnreid\LaravelQuickbooks\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;
use QuickBooksOnline\API\Data\IPPIntuitEntity;
use QuickBooksOnline\API\Data\IPPInvoice;
use QuickBooksOnline\API\DataService\DataService;
use Shawnreid\LaravelQuickbooks\QuickbooksAction;
use Shawnreid\LaravelQuickbooks\QuickbooksClient;

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

    public function test_invoice_sets_facade_property(): void
    {
       $this->action->invoice();

       $this->assertEquals('Invoice', $this->action->getClassName());
    }

    public function test_customer_sets_facade_property(): void
    {
       $this->action->customer();

       $this->assertEquals('Customer', $this->action->getClassName());
    }

    public function test_create_will_throw_error_if_entity_not_specified(): void
    {
        $this->expectError(Error::class);

        $this->action->create([]);
    }

    public function test_update_will_throw_error_if_entity_not_specified(): void
    {
        $this->expectError(Error::class);

        $this->action->update(1, []);
    }

    public function test_delete_will_throw_error_if_entity_not_specified(): void
    {
        $this->expectError(Error::class);

        $this->action->delete(1);
    }

    public function test_find_will_throw_error_if_entity_not_specified(): void
    {
        $this->expectError(Error::class);

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
