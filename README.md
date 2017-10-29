## Tweet-Finder
### Setup
Run the docker compose.
```
docker-composer up -d
```

Install php & node dependencies, run migrations and run `laravel mix`. 
```
docker exec -it tweetfinder_php_1 composer install
docker exec -it tweetfinder_php_1 php artisan migration
docker exec -it tweetfinder_php_1 npm install
docker exec -it tweetfinder_php_1 npm run dev
```
Make sure the you add twitter auth keys in `.env`
```
TWITTER_CONSUMER_KEY=
TWITTER_CONSUMER_SECRET=
TWITTER_ACCESS_TOKEN=
TWITTER_ACCESS_TOKEN_SECRET=
```

### Test
To run phpunit tests, 
```
docker exec -it tweetfinder_php_1 vendor/bin/phpunit
```
You can see the result in [http://localhost](http://localhost).
