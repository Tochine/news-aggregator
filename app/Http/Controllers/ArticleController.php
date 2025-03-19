<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NewsApiService;

class ArticleController extends Controller
{
    protected $newsApiService;

    public function __construct(NewsApiService $newsApiService) {
        $this->newsApiService = $newsApiService;
    }

    public function index(Request $request) {
        $response = $this->newsApiService->fetchAndStoreNewsApiArticle($request);
        if ($response['status'] === 'error') {
            return response()->json($response);
        }
        if ($response->successful()) {
            return $response->json();
        } else {
            return $response->body();
        }
    }
}
