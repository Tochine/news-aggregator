<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class NewsApiService {
    public function fetchAndStoreNewsApiArticle($request) {
        try {
            $params = $request->query();
            $articles = $this->fetchNewsApi($params);
            
            if ($articles['status'] === 'error') {
                Log::error('NewsAPI request failed', [
                    'status' =>  $articles->status(),
                    'response' => $articles->json(),
                ]);
                return $articles->json();
            }
            if ($articles->json(['status']) === 'ok') {
                foreach ($articles->json(['articles']) as $article) {
                    Article::updateOrCreate(
                        ['url' => $article['url']],
                        [
                            'title' => $article['title'],
                            'author' => $article['author'],
                            'description' => $article['description'],
                            'content' => $article['content'],
                            'source' => 'NewsAPI',
                            'category' => $params['q'] ?? 'general',
                            'image_url' => $article['urlToImage'] ?? null,
                            'published_at' => date('Y-m-d H:i:s', strtotime($article['publishedAt'])),
                        ]
                        );
                }

                Log::info('Articles saved successfully');
            }
            return $articles;
        } catch (\Exception $e) {
            Log::error('Error fetching article from NewsAPI', [
                'error' => $e->getMessage()
            ]);
            return $e->getMessage();
        }
    }

    public function fetchNewsApi($params) {
        $articles = Http::get(config('services.newsapi.base_url').'/everything', [
            'apiKey' => config('services.newsapi.key'),
            'q' => $params['q'],
            'page' => $params['page'] ?? 10,
            'language' => $params['language'] ?? 'en',
            'from' => $params['from'] ?? null,
            'to' => $params['to'] ?? null,
        ]);

        return $articles;
    }
}