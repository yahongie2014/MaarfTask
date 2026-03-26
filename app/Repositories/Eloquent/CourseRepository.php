<?php

namespace App\Repositories\Eloquent;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseRepository implements CourseRepositoryInterface
{
    public function paginate(int $perPage = 10, int $categoryId = null): LengthAwarePaginator
    {
        $query = Course::with('category');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        return $query->latest()->paginate($perPage);
    }

    public function create(array $data): Course
    {
        return Course::create($data);
    }

    public function exists(string $youtubeId): bool
    {
        return Course::where('youtube_id', $youtubeId)->exists();
    }

    public function countByCategory(int $categoryId): int
    {
        return Course::where('category_id', $categoryId)->count();
    }
}
