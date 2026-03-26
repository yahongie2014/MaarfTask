<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Course;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Services\YouTubeService;
use Livewire\WithPagination;

class CourseScraper extends Component
{
    use WithPagination;

    public $categoryInput = "Marketing\nProgramming\nGraphic Design\nBusiness\nEngineering";
    public $activeCategoryId = null;

    public function fetchCourses(YouTubeService $service, CategoryRepositoryInterface $categoryRepo, CourseRepositoryInterface $courseRepo)
    {
        $categories = collect(explode("\n", $this->categoryInput))
            ->map(fn($cat) => trim($cat))
            ->filter();

        foreach ($categories as $catName) {
            $category = $categoryRepo->findByName($catName);
            if (!$category) {
                $category = $categoryRepo->create(['name' => $catName]);
            }

            $results = $service->searchCourses($catName);

            foreach ($results as $data) {
                if (!$courseRepo->exists($data['youtube_id'])) {
                    $courseRepo->create([
                        'category_id' => $category->id,
                        'title' => $data['title'],
                        'author' => $data['author'],
                        'thumbnail' => $data['thumbnail'],
                        'youtube_id' => $data['youtube_id'],
                        'description' => $data['description'],
                        'duration' => $data['duration'],
                        'views' => $data['views'],
                    ]);
                }
            }
        }
        
        $this->activeCategoryId = Category::first()?->id;
    }

    public function selectCategory($id)
    {
        $this->activeCategoryId = $id;
        $this->resetPage();
    }

    public function render(CategoryRepositoryInterface $categoryRepo, CourseRepositoryInterface $courseRepo)
    {
        return view('livewire.course-scraper', [
            'categories' => $categoryRepo->all(),
            'courses' => $courseRepo->paginate(12, $this->activeCategoryId),
            'totalCourses' => Course::count(),
            'courseRepo' => $courseRepo,
        ]);
    }
}
