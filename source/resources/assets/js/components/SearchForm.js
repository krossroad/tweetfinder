import slugify from 'slugify';
import param from 'jquery-param';
import {connect} from 'react-redux';
import React, {Component} from 'react';
import {withRouter} from "react-router-dom";
import {Button, Form, FormGroup} from 'react-bootstrap';
import SearchBar from './SearchBar';
import {fetchHistory, fetchTweets, toggleHistoryPanel} from "../reducers/tweetFinder";

class SearchForm extends Component {
    constructor(props) {
        super(props);

        this.handleSearch = this.handleSearch.bind(this);
        this.handleHistoryChange = this.handleHistoryChange.bind(this);
    }

    handleSearch(e) {
        e.preventDefault();
        const {mapCenter, address} = this.props;
        const hash = this.prepareSearchHash(address, mapCenter);

        this.props.history.push(hash);
    }

    handleHistoryChange(e) {
        if (!this.props.showHistory) {
            this.props.fetchHistory();
            return;
        }

        this.props.toggleHistoryPanel();
    }

    prepareSearchHash(address, location) {
        return '/search/' + param({address, ...location});
    }

    render() {
        return (
            <Form className="col-md-12" onSubmit={this.handleSearch}>
                <FormGroup className="col-md-7" controlId="formInlineName">
                    <SearchBar/>
                </FormGroup>
                <Button className="col-xs-10 col-md-2 col-xs-offset-1 col-md-offset-0"
                        disabled={!this.props.canSearch}
                        bsSize="large" type="submit">
                    Search Tweets
                </Button>
                <Button
                    onClick={this.handleHistoryChange}
                    className="col-xs-10 col-md-2 col-xs-offset-1 col-md-offset-0"
                    bsSize="large">
                    History
                </Button>
            </Form>
        )
    }
}

export default connect(
    (state) => (state),
    {fetchTweets, fetchHistory, toggleHistoryPanel}
)(withRouter(SearchForm))
