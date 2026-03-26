<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\CourseResource;
use App\Http\Resources\CategoryResource;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CourseApiController extends Controller
{
    private $courseRepo;
    private $categoryRepo;

    public function __construct(CourseRepositoryInterface $courseRepo, CategoryRepositoryInterface $categoryRepo)
    {
        $this->courseRepo = $courseRepo;
        $this->categoryRepo = $categoryRepo;
    }

    public function courses(): AnonymousResourceCollection
    {
        $courses = $this->courseRepo->paginate(request()->get('per_page', 12), request()->get('category_id'));
        return CourseResource::collection($courses);
    }

    public function categories(): AnonymousResourceCollection
    {
        $categories = $this->categoryRepo->all();
        return CategoryResource::collection($categories);
    }
}
