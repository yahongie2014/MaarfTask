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

    public $search = "";
    public $categoryInput = "";
    public $activeCategoryId = null;

    protected $queryString = ['search'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->categoryInput = __("Marketing") . "\n" . 
                             __("Programming") . "\n" . 
                             __("Graphic Design") . "\n" . 
                             __("Business") . "\n" . 
                             __("Engineering");
    }

    public function fetchCourses(YouTubeService $service, \App\Services\AIService $aiService, CategoryRepositoryInterface $categoryRepo, CourseRepositoryInterface $courseRepo)
    {
        $categories = collect(explode("\n", $this->categoryInput))
            ->map(fn($cat) => (string) trim($cat))
            ->filter();

        foreach ($categories as $catName) {
            $category = $categoryRepo->findByName((string)$catName);
            if (!$category) {
                $category = $categoryRepo->create(['name' => (string)$catName]);
            }

            // AI Integration: Generate 10-20 specific titles
            $titles = $aiService->generateTitles((string)$catName);

            foreach ($titles as $title) {
                // YouTube Search: Get 2 playlists per title
                $results = $service->searchCourses($title, 2);

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
            'courses' => $courseRepo->paginate(12, $this->activeCategoryId, $this->search),
            'totalCourses' => Course::count(),
            'courseRepo' => $courseRepo,
        ]);
    }
}
