import {Fade} from 'react-bootstrap';
import React, {Component} from 'react';
import {connect} from 'react-redux';
import History from './History';
import MapComponent from './Map';
import SearchForm from './SearchForm';
import OverlayMessage from './OverlayMessage';
import {fetchTweets, updatePlace, updateRoteLocation} from './../reducers/tweetFinder';

export const SEARCH_URL = '/search/:searchParams';

class AppCmp extends Component {
    componentWillMount() {
        const {match} = this.props;

        if (SEARCH_URL === match.path && match.isExact) {
            this.props.updateRoteLocation(this.props.location);
        }
    }

    componentDidUpdate(prevProps) {
        const locationChanged = prevProps.routeLocation !== this.props.routeLocation;
        const {match} = this.props;

        if (locationChanged && SEARCH_URL === match.path && match.isExact) {
            const params = new URLSearchParams(match.params.searchParams);
            const location = {lat: parseFloat(params.get('lat')), lng: parseFloat(params.get('lng'))};
            const address = params.get('address');

            this.props.fetchTweets(address, location);
        }
    }

    componentWillReceiveProps(nextProps) {
        const locationChanged = nextProps.location !== this.props.routeLocation;
        const {match} = nextProps;

        if (locationChanged) {
            this.props.updateRoteLocation(nextProps.location);
        }

        if (locationChanged && SEARCH_URL === match.path && match.isExact) {
            const params = new URLSearchParams(match.params.searchParams);
            const location = {lat: parseFloat(params.get('lat')), lng: parseFloat(params.get('lng'))};
            const address = params.get('address');

            this.props.updatePlace({address, location});
        }
    }

    render() {
        return (
            <div className="container-fluid">
                <div className="row well">
                    <div className="col-md-12">
                        <SearchForm/>
                    </div>
                </div>

                <div className="row clearfix">
                    {this.props.showHistory && <Fade in={true} timeout={20000} transitionAppear={true}>
                        <History/>
                    </Fade>}
                    <div className={this.props.showHistory ? `col-md-9 col-xs-12` : `col-md-12`}>
                        <MapComponent/>
                    </div>
                </div>

                <OverlayMessage/>
            </div>
        );
    }
}

export default connect(
    (state) => (state),
    {updateRoteLocation, fetchTweets, updatePlace}
)(AppCmp);
