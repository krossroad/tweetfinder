require('./bootstrap');

import ReactDOM from 'react-dom';
import React, { Component } from 'react';
import {connect, Provider} from 'react-redux';
import {HashRouter, Route, Switch} from 'react-router-dom';
import store from './store';
import App, {SEARCH_URL} from './components/App';

ReactDOM.render(
    <Provider store={store}>
        <HashRouter>
            <Switch>
                <Route path={SEARCH_URL} component={App} />
                <Route path="/" component={App} />
            </Switch>
        </HashRouter>
    </Provider>
    , document.getElementById('app')
);
