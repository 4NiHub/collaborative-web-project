<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    public function index()
    {
        $news = DB::table('news')
            ->select('news_id as id', 'title', 'body as excerpt', 'creation_time as publishedAt')
            ->orderByDesc('creation_time')
            ->get()
            ->map(fn($n) => [
                'id' => $n->id,
                'title' => $n->title,
                'category' => 'Academic',
                'excerpt' => substr($n->excerpt, 0, 80) . '...',
                'publishedAt' => $n->publishedAt,
                'author' => 'Admin'
            ]);

        return [
            'data' => $news,
            'meta' => ['page' => 1, 'limit' => 10, 'total' => $news->count(), 'totalPages' => 1]
        ];
    }

    public function show($id)
    {
        $news = DB::table('news')->where('news_id', $id)->first();
        return [
            'data' => [
                'id' => $news->news_id,
                'title' => $news->title,
                'content' => $news->body,
                'publishedAt' => $news->creation_time,
                'author' => 'Admin'
            ]
        ];
    }
}