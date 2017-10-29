import {connect} from 'react-redux';
import React, { Component } from 'react';
import { compose, withProps, withReducer , pure} from "recompose"
import { GoogleMap, Marker,  withGoogleMap} from "react-google-maps";
import Tweet from './Tweet';

const MapComponent = compose(
    withProps({
        loadingElement: <div style={{ height: `100%` }} />,
        containerElement: <div style={{ height: `88vh` }} />,
        mapElement: <div style={{ height: `100%` }} />,
    }),
    withGoogleMap
)((props) => {
    return (
        <GoogleMap
            defaultZoom={11}
            center={props.mapCenter}>
            {props.tweets.map(tweet => <Tweet key={tweet.id} {...tweet}/>)}
        </GoogleMap>
    )
});

export default connect(
    (state) => (state)
)(MapComponent);
