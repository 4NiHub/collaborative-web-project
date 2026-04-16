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
            ->select('news_id as id', 'title', 'excerpt', 'body', 'category', 'image', 'creation_time as publishedAt')
            ->orderByDesc('creation_time')
            ->get()
            ->map(fn($n) => [
                'id'          => $n->id,
                'title'       => $n->title,
                'category'    => $n->category ?? 'General',
                // If excerpt is empty, generate a short one from the body
                'excerpt'     => $n->excerpt ?? substr($n->body, 0, 80) . '...',
                'image'       => $n->image, // <-- NEW: Send the image to the frontend!
                'publishedAt' => $n->publishedAt,
                'author'      => 'Admin'
            ]);

        return [
            'data' => $news,
            'meta' => ['page' => 1, 'limit' => 10, 'total' => $news->count(), 'totalPages' => 1]
        ];
    }

    public function show($id)
    {
        $news = DB::table('news')->where('news_id', $id)->first();

        if (!$news) {
            return response()->json(['error' => 'News article not found'], 404);
        }

        return [
            'data' => [
                'id'          => $news->news_id,
                'title'       => $news->title,
                'category'    => $news->category ?? 'General',
                'content'     => $news->body,
                'image'       => $news->image, // <-- NEW: Send the image to the popup modal!
                'publishedAt' => $news->creation_time,
                'author'      => 'Admin'
            ]
        ];
    }
}