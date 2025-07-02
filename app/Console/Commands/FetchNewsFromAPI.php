<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;
use Carbon\Carbon;
use GuzzleHttp\Client;


class FetchNewsFromAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to fetch news from apis and add them into the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $from_date = Carbon::now()->subDays(2)->format('Y-m-d\TH:i:s');
        $to_date = Carbon::now()->format('Y-m-d\TH:i:s');
        $search_string = 'a';
        $language = 'en';
        $client = new Client();
        // The URL we want to send the GET request to
        $url = 'https://newsapi.org/v2/everything?q='. $search_string . '&from=' . $from_date . '&to=' . $to_date . '&language=' . $language . '&apiKey=bdec8010568b46d5afd2d9a77225169f';
        // Make the GET request
        $response = $client->get($url);
        $body = $response->getBody()->getContents();
        $results = json_decode($body);
        $total_count = $results->totalResults;
        $page_size = 100;
        $pages_count = 5; //round($total_count/$page_size) api throws error on multiple requests
        for($current_page=1; $current_page<$pages_count; $current_page++){
            $url = 'https://newsapi.org/v2/everything?q='. $search_string . '&from=' . $from_date . '&to=' . $to_date . '&language=' . $language . '&page=' . $current_page . '&apiKey=bdec8010568b46d5afd2d9a77225169f';
            // Make the GET request
            $response = $client->get($url);
            $body = $response->getBody()->getContents();
            $results = json_decode($body);
            foreach($results->articles as $article){
                $news = new News();
                $news->source = $article->source->name;
                $news->author = $article->author;
                $news->title = $article->title;
                $news->description = $article->description;
                $news->url = $article->url;
                $news->urlToImage = $article->urlToImage;
                $news->publishedAt = $article->publishedAt;
                $news->content = $article->content;
                $news->save();
            }
        }
        $this->info('News fetched successfully');
    }
}
