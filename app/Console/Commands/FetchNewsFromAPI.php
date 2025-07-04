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
        $from_date = Carbon::now()->subDay()->format('Y-m-d\TH:i:s');
        $to_date = Carbon::now()->format('Y-m-d\TH:i:s');
        $search_string = 'a';
        $language = 'en';
        $client = new Client();
        $page_size = 50;
        $pages_count = 3; //round($total_count/$page_size) api throws error on multiple requests
        $data=[];
        for($current_page=1; $current_page<$pages_count; $current_page++){
            $news_api_url = 'https://newsapi.org/v2/everything?q='. $search_string . '&from=' . $from_date . '&to=' . $to_date . '&language=' . $language . '&pageSize=' . $page_size . '&page=' . $current_page . '&apiKey=' . config('app.new_api_key');
            // Make the GET request
            try {
                $response = $client->get($news_api_url);
                $body = $response->getBody()->getContents();
                $results = json_decode($body);
            }
            catch (\GuzzleHttp\Exception\RequestException $e) {
                $this->info('Error (news_api): '. $e->getResponse());
            }
            foreach($results->articles as $article){
                $data[] = [
                    'source' => isset($article->source->name) ? $article->source->name : 'NewsApi',
                    'author' => $article->author,
                    'title' => $article->title,
                    'description' => $article->description,
                    'url' => $article->url,
                    'urlToImage' => $article->urlToImage,
                    'publishedAt' => $article->publishedAt,
                    'content' => $article->content
                ];
            }
            $guardian_news_api_url = 'https://content.guardianapis.com/search?q='. $search_string . '&from-date=' . $from_date . '&to-date=' . $to_date . '&lang=' . $language . '&show-tags=contributor' . '&page-size=' . $page_size . '&page=' . $current_page . '&show-fields=bodyText,byline,thumbnail' . '&api-key=' . config('app.guardian_new_api_key');
            try{
                $response = $client->get($guardian_news_api_url);
                $body = $response->getBody()->getContents();
                $results = json_decode($body);
            }
            catch (\GuzzleHttp\Exception\RequestException $e) {
                $this->info('Error (guardian_apis): '. $e->getResponse());
            }
            foreach($results->response->results as $article){
                $data[] = [
                    'source' => 'The Guardian',
                    'author' => isset($article->fields->byline) ? $article->fields->byline : '',
                    'title' => $article->webTitle,
                    'description' => $article->webTitle,
                    'url' => $article->webUrl,
                    'urlToImage' => isset($article->fields->thumbnail) ? $article->fields->thumbnail : '',
                    'publishedAt' => $article->webPublicationDate,
                    'content' => $article->fields->bodyText
                ];
            }
            $g_news_url = 'https://gnews.io/api/v4/search?q='. $search_string . '&from=' . $from_date . '&to=' . $to_date . '&lang=' . $language . '&page=' . 2 . '&apikey=' . config('app.g_news_api_key');
            try{
                $response = $client->get($g_news_url);
                $body = $response->getBody()->getContents();
                $results = json_decode($body);
            }
            catch (\GuzzleHttp\Exception\RequestException $e) {
                $this->info('Error (g_news_api): '. $e->getResponse());
            }
            foreach($results->articles as $article){
                $data[] = [
                    'source' => isset($article->source->name) ? $article->source->name : 'GNews',
                    'author' => '', //this api don't return author
                    'title' => $article->title,
                    'description' => $article->description,
                    'url' => $article->url,
                    'urlToImage' => $article->image,
                    'publishedAt' => $article->publishedAt,
                    'content' => $article->content
                ];
            }
        }
        News::insert($data);
        $this->info('News fetched successfully');
        return 'News fetched successfully';
    }
}
