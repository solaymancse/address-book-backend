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

    public function createRecord(array $data): AddressBook
    {
        $data['created_by'] = auth()->id();
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
