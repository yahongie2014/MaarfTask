<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function all(): Collection
    {
        return Category::withCount('courses')->get();
    }

    public function findByName(string $name): ?Category
    {
        return Category::where('name', $name)->first();
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }
}
