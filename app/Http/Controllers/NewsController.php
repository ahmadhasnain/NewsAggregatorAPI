<?php

namespace App\Http\Controllers;

use App\Models\News;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Traits\ApiResponser;

class NewsController extends Controller
{
    use ApiResponser;

    public function get(Request $request)
    {
        return $this->success(News::all());
    }

    public function getSources(Request $request)
    {
        return $this->success(News::whereNotNull('source')->groupBy('source')->pluck('source')->toArray());
    }

    public function getAuthors(Request $request)
    {
        return $this->success(News::whereNotNull('author')->groupBy('author')->pluck('author')->toArray());
    }
}
