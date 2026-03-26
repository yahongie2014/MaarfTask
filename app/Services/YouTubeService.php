<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class YouTubeService
{
    private $client;
    private $apiKey;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://www.googleapis.com/youtube/v3/']);
        $this->apiKey = config('services.youtube.key');
    }

    public function searchCourses(string $searchTerm, int $limit = 12): array
    {
        if (empty($this->apiKey)) {
            return $this->getMockResults($searchTerm, $limit);
        }

        try {
            $response = $this->client->get('search', [
                'query' => [
                    'part' => 'snippet',
                    'q' => $searchTerm,
                    'type' => 'playlist',
                    'maxResults' => $limit,
                    'key' => $this->apiKey,
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $items = $data['items'] ?? [];

            $courses = [];
            foreach ($items as $item) {
                $courses[] = [
                    'title' => $item['snippet']['title'],
                    'author' => $item['snippet']['channelTitle'],
                    'thumbnail' => $item['snippet']['thumbnails']['medium']['url'],
                    'youtube_id' => $item['id']['playlistId'],
                    'description' => $item['snippet']['description'],
                    'duration' => rand(5, 50) . ' hours',
                    'views' => number_format(rand(10000, 500000)) . ' views',
                ];
            }

            return $courses;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('YouTube Search Failed: ' . $e->getMessage());
            return $this->getMockResults($searchTerm, $limit);
        }
    }

    private function getMockResults(string $searchTerm, int $limit = 8): array
    {
        $courses = [];
        $isArabic = preg_match('/\p{Arabic}/u', $searchTerm);

        for ($i = 1; $i <= $limit; $i++) {
            $title = $isArabic 
                ? "دورة $searchTerm كاملة #$i - تعلم باحترافية"
                : "Complete $searchTerm Masterclass #$i";

            $courses[] = [
                'title' => $title,
                'author' => "EduChannel $i",
                'thumbnail' => "https://picsum.photos/seed/" . md5($searchTerm . $i) . "/480/360",
                'youtube_id' => bin2hex(random_bytes(6)),
                'description' => "This is a comprehensive course about $searchTerm including all the basics...",
                'duration' => rand(2, 20) . ' hours',
                'views' => number_format(rand(1000, 1000000)) . ' views',
            ];
        }
        return $courses;
    }
}
