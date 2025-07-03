<?php

namespace App\Http\Controllers;

use App\Models\News;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Artisan;

class NewsController extends Controller
{
    use ApiResponser;

    /**
     * @param Request $request
     * 
     * @return ApiResponser $response
    */
    public function get(Request $request)
    {
        $page_size = $request->input('page_size');
        $current_page = $request->input('current_page');
        $filters = $request->input('filters');
        
        $news = News::when(isset($filters['search']), function($query) use($filters){
                        $search = '%' . $filters['search'] . '%';
                        return $query->where(function($query) use ($search){
                            $query->where('title', 'LIKE', $search)
                                ->orWhere('description', 'LIKE', $search)
                                ->orWhere('content', 'LIKE', $search)
                                ->orWhere('source', 'LIKE', $search)
                                ->orWhere('author', 'LIKE', $search);
                        });
                    })
                    ->when(isset($filters['sources']), function($query) use($filters){
                        return $query->whereIn('source', $filters['sources']);
                    })
                    ->when(isset($filters['authors']), function($query) use($filters){
                        return $query->whereIn('author', $filters['authors']);
                    })->paginate($page_size, ['*'], 'current_page', $current_page);
                    
        return $this->success(['news'=>$news]);
    }

    /**
     * @param Request $request
     * 
     * @return ApiResponser $response
    */
    public function getSources(Request $request)
    {
        return $this->success(News::whereNotNull('source')->groupBy('source')->pluck('source')->toArray());
    }

    /**
     * @param Request $request
     * 
     * @return ApiResponser $response
    */
    public function getAuthors(Request $request)
    {
        return $this->success(News::whereNotNull('author')->groupBy('author')->pluck('author')->toArray());
    }

    /**
     * @param string $command
     * @param string $param
     * 
     * @return ApiResponser $response
    */
    public function fetch_news_command($command, $param){
        $artisan = Artisan::call($command.":".$param);
        $output = Artisan::output();
        return $this->success($output);
    }
}
