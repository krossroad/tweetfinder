import param from 'jquery-param';

const prepareApiUrl = (query, location) => {
    const TWEET_API_URL = '/api/tweets/';
    return TWEET_API_URL + '?' + param({query, ...location});
};

export const getTweets = (query, location) => {
    return fetch(prepareApiUrl(query, location), {
        headers: {Accept: 'application/json'},
    })
        .then(res => res.json())
};

