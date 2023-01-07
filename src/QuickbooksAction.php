<?php

declare(strict_types=1);

namespace Shawnreid\LaravelQuickbooks;

use QuickBooksOnline\API\Data\IPPIntuitEntity;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\Invoice;

class QuickbooksAction
{
    public function __construct(
        private DataService $dataService,
        private string $facade = ''
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
     * Quickbooks invoice facade
     *
     * @return self
     */
    public function invoice(): self
    {
        $this->facade = Invoice::class;

        return $this;
    }

    /**
     * Quickbooks customer facade
     *
     * @return self
     */
    public function customer(): self
    {
        $this->facade = Customer::class;

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
        if (empty($this->facade)) {
            throw new \Error('No entity type specified.');
        }

        return $this->dataService->Add(
            $this->facade::create($body)
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
        if (empty($this->facade)) {
            throw new \Error('No entity type specified.');
        }

        return $this->dataService->Add(
            $this->facade::update($this->find($id), $body)
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
        if (empty($this->facade)) {
            throw new \Error('No entity type specified.');
        }

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
        if (empty($this->facade)) {
            throw new \Error('No entity type specified.');
        }

        return $this->dataService->findById($this->getClassName(), $id);
    }

    /**
     * Get class name of specified facades
     *
     * @return string
     */
    private function getClassName(): string
    {
        return substr((string) strrchr($this->facade, '\\'), 1);
    }
}
