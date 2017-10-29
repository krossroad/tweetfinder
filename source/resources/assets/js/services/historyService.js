const SEARCH_API_URL = '/api/search-history';

export const getHistory = () => {
    return fetch(SEARCH_API_URL, {
        headers: {Accept: 'application/json'}
    })
        .then(res => res.json());
};
