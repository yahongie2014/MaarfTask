<?php

namespace App\Services;

class AIService
{
    /**
     * Generate 10-20 course titles related to the category.
     */
    public function generateTitles(string $category): array
    {
        // In a real app, this would call OpenAI/Anthropic API.
        // For this task, I'll simulate highly relevant results.
        
        $isArabic = preg_match('/\p{Arabic}/u', $category);

        if ($isArabic) {
            return [
                "دورة $category للمبتدئين من الصفر",
                "أسرار الـ $category والمحتوى الرقمي",
                "تعلم $category باحترافية في 2026",
                "دبلومة $category المتقدمة",
                "مشروع عملي: بناء نظام $category",
                "أساسيات $category والنمو السريع",
                "خارطة طريق لتعلم $category",
                "أدوات $category المجانية والمدفوعة",
                "الذكاء الاصطناعي في الـ $category",
                "نصائح الخبراء في مجال $category",
            ];
        }

        return [
            "Complete $category for Beginners",
            "Advanced $category Masterclass",
            "$category: The Ultimate Roadmap",
            "Full Stack $category Development",
            "$category Strategies for 2026",
            "The Science of $category Explained",
            "Hands-on $category Projects",
            "10 Shortcuts to Master $category",
            "$category Industry Standards and Best Practices",
            "Future of $category with AI",
        ];
    }
}
