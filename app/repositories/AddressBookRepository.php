<?php

namespace App\Repositories;

use App\Interfaces\AddressBookRepositoryInterface;
use App\Models\AddressBook;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AddressBookRepository implements AddressBookRepositoryInterface {
    public function getFilteredPaginated(array $filters): LengthAwarePaginator {
        $query = AddressBook::with('creator');

        // Server-side search (name, email, phone)
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filtering by gender
        if (!empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }

        // Filtering by nationality
        if (!empty($filters['nationality'])) {
            $query->where('nationality', $filters['nationality']);
        }

        // Filtering by age range
        if (!empty($filters['min_age'])) {
            $query->where('age', '>=', $filters['min_age']);
        }
        if (!empty($filters['max_age'])) {
            $query->where('age', '<=', $filters['max_age']);
        }

        $perPage = $filters['per_page'] ?? 10;
        return $query->latest()->paginate($perPage);
    }

    public function findById(int $id): ?AddressBook {
        return AddressBook::with('creator')->find($id);
    }

    public function create(array $data): AddressBook {
        return AddressBook::create($data);
    }

    public function update(AddressBook $addressBook, array $data): AddressBook {
        $addressBook->update($data);
        return $addressBook;
    }

    public function delete(AddressBook $addressBook): bool {
        return $addressBook->delete();
    }
}