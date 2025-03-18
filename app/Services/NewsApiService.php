<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class NewsApiService {
    public function fetchNewsApiArticle($request) {
        try {
            $params = $request->query();
            $response = Http::get(config('services.newsapi.base_url').'/everything', [
                'apiKey' => config('services.newsapi.key'),
                'q' => $params['q'],
                'pageSize' => $pageSize ?? 10,
                // 'country' => $params['country'] ?? null,
                'language' => $params['language'] ?? 'en',
                'from' => $params['from'] ?? null,
                'to' => $params['to'] ?? null,
            ]);
            
            if($response->failed()) {
                Log::error('NewsAPI request failed', [
                    'status' =>  $response->status(),
                    'response' => $response->json(),
                ]);
                return $response->json();
            }
            return $response;
        } catch (\Exception $e) {
            Log::error('Error fetching article from NewsAPI', [
                'error' => $e->getMessage()
            ]);
            return $e->getMessage();
        }
    }
}