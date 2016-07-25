<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Config;
use DateTime;
use Exception;
use Session;
use Twitter;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params = [];

        if(Session::has(Config::get('constants.TWITTER_ACCESS_TOKEN'))) {
            $params['twitter_connected'] = true;
        }

        try {
            $params['tweets'] = $this->getRelatedTweets();
        } catch(Exception $e) {
            $params['error'] = $e->getMessage();
        }

        return view('home', $params);
    }

    /**
     * Gets personalized tweets to show the user
     * @return array The tweets to show
     */
    protected function getRelatedTweets()
    {
        //get the search terms from yahoo
        $searchTerms = [
            'tom brady',
            'jaylon smith'
        ];

        $results = [];
        $limitPerTerm = Config::get('constants.FEED_SIZE') / count($searchTerms);

        //find some tweets related to the search terms
        foreach ($searchTerms as $searchTerm) {
            $newTweets = $this->searchTweets($searchTerm, $limitPerTerm);
            $results = array_merge($results, $newTweets->statuses);
        }

        //sort the aggregated results
        uasort($results, function($firstItem, $secondItem) {
            $firstDate = new DateTime($firstItem->created_at);
            $secondDate = new DateTime($secondItem->created_at);
            return $firstDate < $secondDate;
        });

        //turn the results into html
        $results = $this->getOembedTweets($results);

        return $results;
    }

    /**
     * Search Twitter for tweets containing the search term
     * @param  string  $searchTerm The term to search twitter for
     * @param  integer $count      The max amount of results to return
     * @return array               Tweets containing the search term
     */
    protected function searchTweets($searchTerm, $count = 10)
    {
        $twitterParameters = [
            'q' => $searchTerm,
            'result_type' => 'mixed',
            'count' => $count,
            'include_entities' => false,
        ];

        return Twitter::getSearch($twitterParameters);
    }

    /**
     * Turns an array of tweets into an array of html elements
     * @param  array $tweets The array of tweets to turn into html
     * @return array         Array of tweets in html form
     */
    protected function getOembedTweets($tweets)
    {
        $formattedTweets = [];
        foreach ($tweets as $tweet) {
            $formattedTweets[] = Twitter::getOembed(['id' => $tweet->id]);
        }
        return $formattedTweets;
    }
}
