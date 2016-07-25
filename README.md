# Pheme
Pheme is a fantasy sports application which provides users with the latest news about their fantasy teams. Pheme won't discriminate between legitimate news sources and baseless rumors, so you'll hear every little rumbling about your players!

*Pheme was the Greek god most associated with rumors and gossip*

# Usage

Just go to pheme.herokuapp.com

If you really want to build it yourself,
- Clone the repo
- Run composer install to pull down the dependencies
- Setup .env file with your credentials for laravel, database(s), twitter, and yahoo.
- Run php artisan migrate (unless you're using heroku local, in which case my procfile should run any migrations).
- Serve it using heroku local or a local webserver of your choice, pointing all non-matched routes at at public/index.php
(look at my nginx.conf file to see an example).

# Technology
Pheme makes use of the following technologies:
- https://dev.twitter.com/rest/reference/get/search/tweets
- https://developer.yahoo.com/fantasysports/
- Heroku
- Laravel
- PHP

# Implementation details
- I decided to use Laravel because I knew it had some pretty awesome functionality out of the box. It also has a very
large and active community, so I thought it would be very likely to have some helpful libraries I could use.
- I've also heard how easy it is to get apps up and running with heroku, but have never actually played with it myself.
So I took this chance to finally try it out (I like it).
- I didn't add any unit tests because I only had 4 hours and didn't want to spend it on tests whose real benefit is long
term development speed, stability, and maintainability in general. If this were a production level project, I would have
started by setting up tests and going with more of a TDD approach.
- I misjudged how much time I had so I really didn't get to some of the interesting things I had in mind. The general idea
was to connect fantasy football apps (just yahoo for this timebox) with news and social media (just twitter to start)
services in order to deliver the most important and up to date information possible. Instead, I didn't finish connecting
with yahoo so left the search terms stubbed out for the twitter feed integration (tom brady and jaylon smith).
- Assuming I actually did finish this, here's how I would approach making this a production-quality service:
 - Throw this project out. I wouldn't want to fall into the trap of continuing to build on MVPs because some of the work
 is already done.
 - Implement unit testing as well as continuous integration.
 - Use caching to improve performance. I would also use it to avoid potential rate limiting issues with the service APIs
 which I'm sure I would run into with more users or even just a larger feed.
 - Encapsulate more of the dependent logic. For example, I'm not a huge fan of all that logic being in the Twitter and
 Home controllers, so I would like to take most of that functionality out of the controllers and put them behind a
 suitable interface.
 - Improve user interface. Obviously I didn't put much time into the way this thing looked... I just used the laravel
 scaffolding and threw the feeds/links onto the home page.
 - Iterate on features like multiple fantasy applications and/or sports, as well as multiple news sources (other social
 media platforms, actual news sources, etc.). Also try to improve the logic of what's served when and in what order
 (from something as simple as prioritizing verified twitter accounts to more complex prediction like analyzing tweet
 composition with common word clouds or something).
