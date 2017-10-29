import {createStore, applyMiddleware} from 'redux';
import tweetFinder from './reducers/tweetFinder';
import thunk from 'redux-thunk';

export default createStore(
    tweetFinder,
    applyMiddleware(thunk)
);
