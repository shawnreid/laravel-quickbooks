<?php

declare(strict_types=1);

namespace Shawnreid\LaravelQuickbooks;

use QuickBooksOnline\API\Data\IPPIntuitEntity;
use QuickBooksOnline\API\DataService\DataService;

class QuickbooksAction
{
    public function __construct(
        private DataService $dataService,
        private string $entity = ''
    ) { }

    /**
     * Set entity type
     *
     * @param string $function
     * @param array $arguments
     * @return self
     */
    public function __call(string $function, array $arguments): self
    {
        $class = '\\QuickBooksOnline\\API\\Facades\\' . ucfirst($function);

        if (class_exists($class)) {
            $this->entity = $class;
            return $this;
        }

        throw new \Error(sprintf('Call to undefined method %s::%s', __CLASS__, $function));
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
}
