import React, {Component} from 'react';
import {connect} from 'react-redux';
import {FormControl} from 'react-bootstrap';
import {compose, withProps} from "recompose";
import {disableSearch, enableSearch, updateAddress, updatePlace} from './../reducers/tweetFinder';
import StandaloneSearchBox from 'react-google-maps/lib/components/places/StandaloneSearchBox';

const refs = {};

class SearchBarComponent extends Component {
    constructor(props) {
        super(props);

        this.placeChangeHandler = this.placeChangeHandler.bind(this);
        this.handleAddressChange = this.handleAddressChange.bind(this);
    }

    onSearchBoxMount(ref) {
        refs.searchBox = ref;
    }

    placeChangeHandler() {
        if (refs.searchBox.getPlaces()) {
            const [place] = refs.searchBox.getPlaces();

            this.props.updatePlace({
                location: place.geometry.location.toJSON(),
                address: place.name
            });
        }
    }

    handleAddressChange(e) {
        this.props.udpateAddress(e.target.value);
        if (e.target.value !== this.props.queryAddress) {
            this.props.disableSearch();
        } else {
            this.props.enableSearch()
        }
    }

    render() {
        return (
            <StandaloneSearchBox
                ref={this.onSearchBoxMount}
                onPlacesChanged={this.placeChangeHandler}>
                <FormControl placeholder="Place..." className="col-md-7" bsSize="large"
                             onChange={this.handleAddressChange}
                             value={this.props.address}/>
            </StandaloneSearchBox>
        );
    }
}

const SearchBar = compose(
    withProps({
        loadingElement: <div style={{height: `100%`}}/>,
        containerElement: <div style={{height: `400px`}}/>,
    })
)(SearchBarComponent);

export default connect(
    (state) => (state),
    {updatePlace, udpateAddress: updateAddress, disableSearch, enableSearch}
)(SearchBar);
