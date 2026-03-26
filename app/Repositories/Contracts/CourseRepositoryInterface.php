<?php

namespace App\Repositories\Contracts;

use App\Models\Course;
use Illuminate\Pagination\LengthAwarePaginator;

interface CourseRepositoryInterface
{
    public function paginate(int $perPage = 10, int $categoryId = null, string $search = null): LengthAwarePaginator;
    public function create(array $data): Course;
    public function exists(string $youtubeId): bool;
    public function countByCategory(int $categoryId): int;
}
