<?php

namespace App\Services;

use App\Interfaces\AddressBookRepositoryInterface;
use App\Models\AddressBook;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AddressBookService
{
    protected AddressBookRepositoryInterface $repository;

    public function __construct(AddressBookRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function listRecords(array $filters): LengthAwarePaginator
    {
        return $this->repository->getFilteredPaginated($filters);
    }

    public function getRecord(int $id): ?AddressBook
    {
        return $this->repository->findById($id);
    }

    public function createRecord(array $data, int $userId): AddressBook
    {
        // Enforce that created_by is derived securely from the authenticated user token
        $data['created_by'] = $userId;
        return $this->repository->create($data);
    }

    public function updateRecord(AddressBook $addressBook, array $data): AddressBook
    {
        return $this->repository->update($addressBook, $data);
    }

    public function deleteRecord(AddressBook $addressBook): bool
    {
        return $this->repository->delete($addressBook);
    }
}
