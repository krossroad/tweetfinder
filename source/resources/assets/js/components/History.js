import React, {Component} from 'react';
import {connect} from "react-redux";
import {Link} from 'react-router-dom';
import {Badge} from 'react-bootstrap';
import param from 'jquery-param';
import {toggleHistoryPanel} from './../reducers/tweetFinder';
 
class History extends Component {
    render() {
        return (
            <div className="col-md-3 col-xs-12 list-group">
                <h2 className="text-center">Search History</h2>
                {this.props.history.map(history => (
                    <Link
                        to={`/search/${param(history.payload)}`}
                        className="list-group-item"
                        replace={false}
                        onClick={this.props.toggleHistoryPanel}
                        key={history.key}>
                        {history.payload.address}
                        <Badge title="Visit Count">{history.count}</Badge>
                    </Link>
                ))}
            </div>
        );
    }
}

export default connect(
    (state) => (state),
    {toggleHistoryPanel}
)(History);
