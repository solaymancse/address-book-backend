<?php

namespace App\Interfaces;

use App\Models\AddressBook;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AddressBookRepositoryInterface
{
    public function getFilteredPaginated(array $filters): LengthAwarePaginator;
    public function findById(int $id): ?AddressBook;
    public function create(array $data): AddressBook;
    public function update(AddressBook $addressBook, array $data): AddressBook;
    public function delete(AddressBook $addressBook): bool;
}
