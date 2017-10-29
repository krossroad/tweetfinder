import {getTweets} from './../services/tweetService';
import {getHistory} from "../services/historyService";

const PLACE_CHANGE = 'PLACE_CHANGE';
const ADDRESS_CHANGE = 'ADDRESS_CHANGE';
const UPDATE_TWEETS = 'UPDATE_TWEETS';
const DISABLE_SEARCH = 'DISABLE_SEARCH';
const ENABLE_SEARCH = 'ENABLE_SEARCH';
const TOGGLE_HISTORY = 'TOGGLE_HISTORY';
const UPDATE_HISTORY = 'UPDATE_HISTORY';
const UPDATE_ROUTE_LOCATION = 'UPDATE_ROUTE_LOCATION';
const CHANGE_OVERLAY = 'CHANGE_OVERLAY';

const initialState = {
    mapCenter: {
        lat: -34.397,
        lng: 150.644
    },
    showHistory: false,
    queryAddress: '',
    canSearch: false,
    address: '',
    tweets: [],
    history: [],
    routeLocation: {},
    overlayMessage: '',
    showOverlay: false
};

export const updatePlace = (place) => ({type: PLACE_CHANGE, payload: place});
export const updateAddress = (address) => ({type: ADDRESS_CHANGE, payload: address});
export const updateTweets = (tweets) => ({type: UPDATE_TWEETS, payload: tweets});
export const disableSearch = () => ({type: DISABLE_SEARCH});
export const enableSearch = () => ({type: ENABLE_SEARCH});
export const toggleHistoryPanel = () => ({type: TOGGLE_HISTORY});
export const updateHistory = (historyDump) => ({type: UPDATE_HISTORY, payload: historyDump});
export const updateRoteLocation = (location) => ({type: UPDATE_ROUTE_LOCATION, payload: location});
export const changeOverlayMessage = (showOverlay, overlayMessage = '') => ({
    type: CHANGE_OVERLAY,
    payload: { showOverlay, overlayMessage }
});

export const fetchTweets = (address, location) => {
    return (dispatch) => {
        dispatch(changeOverlayMessage(true, 'Fetching Tweets.'))

        getTweets(address, location)
            .then(res => dispatch(updateTweets(res.tweets)))
            .then(() => dispatch(changeOverlayMessage(false)), () => dispatch(changeOverlayMessage(false)));
    }
};

export const fetchHistory = () => {
    return (dispatch) => {
        dispatch(changeOverlayMessage(true, 'Fetching history.'))
        getHistory()
            .then(res => {
                dispatch(updateHistory(res.history));
                dispatch(toggleHistoryPanel());
            })
            .then(() => dispatch(changeOverlayMessage(false)), () => dispatch(changeOverlayMessage(false)))
    };
};

export default (state = initialState, action) => {
    switch (action.type) {
        case PLACE_CHANGE:
            return {...state,
                mapCenter: action.payload.location,
                queryAddress: action.payload.address,
                address: action.payload.address,
                canSearch: true,
                tweets: []
            };

        case ADDRESS_CHANGE:
            return {...state, address: action.payload};

        case DISABLE_SEARCH:
            return {...state, canSearch: false};

        case ENABLE_SEARCH:
            return {...state, canSearch: true};

        case UPDATE_TWEETS:
            return {...state, tweets: action.payload};

        case UPDATE_HISTORY:
            return {...state, history: action.payload};

        case TOGGLE_HISTORY:
            return {...state, showHistory: !state.showHistory};

        case UPDATE_ROUTE_LOCATION:
            return {...state, routeLocation: action.payload};

        case CHANGE_OVERLAY:
            return { ...state, showOverlay: action.payload.showOverlay, overlayMessage: action.payload.overlayMessage }

        default:
            return state;
    }
};
