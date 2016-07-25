<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Config;
use Input;
use Redirect;
use Session;
use Twitter;

class YahooController extends Controller
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
     * Log in with yahoo via oauth. Taken from https://github.com/oriceon/oauth-5-laravel
     *
     * @return \Illuminate\Http\Response
     */
    public function loginWithYahoo(Request $request)
    {
        // get data from request
        $token  = $request->get('oauth_token');
        $verify = $request->get('oauth_verifier');

        \OAuth::setHttpClient('CurlClient');

        // get yahoo service
        $yh = \OAuth::consumer('Yahoo');

        // if code is provided get user data and sign in
        if ( ! is_null($token) && ! is_null($verify))
        {
            // This was a callback request from yahoo, get the token
            $token = $yh->requestAccessToken($token, $verify);

            $xid = [$token->getExtraParams()];
            $result = json_decode($yh->request('https://social.yahooapis.com/v1/user/' . $xid[0]['xoauth_yahoo_guid'] . '/profile?format=json'), true);

            //Var_dump
            //display whole array.
            dd($result);
        }
        // if not ask for permission first
        else
        {
            // get request token
            $reqToken = $yh->requestRequestToken();

            // get Authorization Uri sending the request token
            $url = $yh->getAuthorizationUri(['oauth_token' => $reqToken->getRequestToken()]);

            // return to yahoo login url
            return redirect((string)$url);
        }
    }

    /**
     * The twitter oauth callback. Taken from https://github.com/thujohn/twitter
     *
     * @return \Illuminate\Http\Response
     */
    public function loginCallback()
    {
        // You should set this route on your Twitter Application settings as the callback
        // https://apps.twitter.com/app/YOUR-APP-ID/settings
        if (Session::has('oauth_request_token'))
        {
            $request_token = [
                'token'  => Session::get('oauth_request_token'),
                'secret' => Session::get('oauth_request_token_secret'),
            ];

            Twitter::reconfig($request_token);

            $oauth_verifier = false;

            if (Input::has('oauth_verifier'))
            {
                $oauth_verifier = Input::get('oauth_verifier');
            }

            // getAccessToken() will reset the token for you
            $token = Twitter::getAccessToken($oauth_verifier);

            if (!isset($token['oauth_token_secret']))
            {
                return Redirect::route('twitter.login')->with('flash_error', 'We could not log you in on Twitter.');
            }

            $credentials = Twitter::getCredentials();

            if (is_object($credentials) && !isset($credentials->error))
            {
                // $credentials contains the Twitter user object with all the info about the user.
                // Add here your own user logic, store profiles, create new users on your tables...you name it!
                // Typically you'll want to store at least, user id, name and access tokens
                // if you want to be able to call the API on behalf of your users.

                // This is also the moment to log in your users if you're using Laravel's Auth class
                // Auth::login($user) should do the trick.

                Session::put(Config::get('constants.twitter_access_token'), $token);

                return Redirect::to('/home')->with('flash_notice', 'Congrats! You\'ve successfully signed in!');
            }

            return Redirect::route('twitter.error')->with('flash_error', 'Crab! Something went wrong while signing you up!');
        }
    }
}
