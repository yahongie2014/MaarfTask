<div class="max-w-7xl mx-auto px-4 py-8">
    <header class="mb-12">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-white flex items-center">
                <span class="text-red-500 mr-2 ml-2">YouTube</span> {{ __('Course Scraper') }}
            </h1>
            <div class="flex items-center space-x-2 space-x-reverse text-sm text-gray-400">
                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                <span>{{ __('System Active') }}</span>
            </div>
        </div>

        <div class="bg-[#1e293b] rounded-2xl p-8 border border-white/5 shadow-2xl">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div class="order-2 lg:order-1">
                    <h2 class="text-2xl font-semibold mb-4 text-white text-right-rtl">{{ __('Collect Educational Courses from YouTube') }}</h2>
                    <p class="text-gray-400 mb-6">{{ __('Enter categories, and the system will search and fetch top educational courses automatically.') }}</p>
                    <div class="flex space-x-4 space-x-reverse">
                        <button wire:click="fetchCourses" wire:loading.attr="disabled" class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-xl font-semibold transition flex items-center shadow-lg shadow-red-600/20">
                            <span wire:loading.remove>{{ __('Start Fetching') }}</span>
                            <span wire:loading class="flex items-center">
                                <svg class="animate-spin h-5 w-5 text-white mr-2 ml-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ __('Searching...') }}
                            </span>
                        </button>
                        <button class="bg-white/5 hover:bg-white/10 text-white px-8 py-3 rounded-xl font-semibold transition border border-white/10">
                            {{ __('Stop') }}
                        </button>
                    </div>
                </div>
                <div class="order-1 lg:order-2">
                    <label class="block text-sm font-medium text-gray-400 mb-2">{{ __('Enter categories (one per line):') }}</label>
                    <textarea wire:model="categoryInput" rows="5" dir="ltr" class="w-full bg-[#0f172a] border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition resize-none"></textarea>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="flex flex-col space-y-8">
            <div class="flex items-baseline justify-between">
                <h3 class="text-xl font-semibold">{{ __('Discovered Courses') }}</h3>
                <span class="text-sm text-gray-400">{{ __('Found :count courses', ['count' => $totalCourses]) }}</span>
            </div>

            <div class="flex flex-wrap gap-2">
                <button wire:click="selectCategory(null)" class="px-5 py-2.5 rounded-full text-sm font-medium transition {{ is_null($activeCategoryId) ? 'bg-red-600 text-white shadow-lg shadow-red-600/20' : 'bg-white/5 text-gray-400 hover:bg-white/10' }}">
                    {{ __('All') }} ({{ $totalCourses }})
                </button>
                @foreach($categories as $category)
                    <button wire:click="selectCategory({{ $category->id }})" class="px-5 py-2.5 rounded-full text-sm font-medium transition {{ $activeCategoryId == $category->id ? 'bg-red-600 text-white shadow-lg shadow-red-600/20' : 'bg-white/5 text-gray-400 hover:bg-white/10' }}">
                        {{ __($category->name) }} ({{ $category->courses_count }})
                    </button>
                @endforeach
            </div>

            <div class="relative max-w-md">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="{{ __('Search courses by title...') }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none rtl:left-0 rtl:right-auto rtl:pl-3">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($courses as $course)
                    <div class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden hover:border-red-500/50 transition-all duration-300 group">
                        <div class="relative">
                            <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="https://www.youtube.com/playlist?list={{ $course->youtube_id }}" target="_blank" class="bg-red-600 p-3 rounded-full hover:bg-red-700 transition">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </a>
                            </div>
                            <span class="absolute top-3 right-3 rtl:left-3 rtl:right-auto bg-red-600 text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded">{{ __('Course') }}</span>
                        </div>
                        <div class="p-5">
                            <div class="flex items-center space-x-2 space-x-reverse text-[10px] font-medium text-gray-400 mb-3 uppercase tracking-widest">
                                <span>{{ $course->duration }}</span>
                                <span>•</span>
                                <span>{{ $course->views }}</span>
                            </div>
                            <h4 class="font-bold text-base mb-3 line-clamp-2 min-h-[3rem] group-hover:text-red-500 transition">{{ $course->title }}</h4>
                            <div class="flex items-center justify-between mt-4">
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <div class="w-6 h-6 bg-white/10 rounded-full flex items-center justify-center text-[10px]">👤</div>
                                    <span class="text-xs text-gray-400 line-clamp-1">{{ $course->author }}</span>
                                </div>
                                <span class="text-[10px] bg-white/10 text-gray-300 py-1 px-2 rounded">{{ $course->category->name }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 flex justify-center">
                {{ $courses->links('livewire.custom-pagination') }}
            </div>
        </div>
    </main>
</div>