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
        //dd($request->query());
        $response = $this->newsApiService->fetchNewsApiArticle($request);
        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 400);
        }
        if ($response->successful()) {
            return $response->json();
        } else {
            return $response->body();
        }

    }
}
