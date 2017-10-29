import React, {Component} from 'react';
import {OverlayView, InfoWindow} from 'react-google-maps';

const getPixelPositionOffset = (width, height) => ({
    x: -(width / 2),
    y: -(height / 2),
});

export default class Tweet extends Component {
    constructor(props) {
        super(props);

        this.state = {isOpen: false};
        this.handleMarkerClick = this.handleMarkerClick.bind(this);
    }

    handleMarkerClick() {
        this.setState({
            isOpen: !this.state.isOpen
        });
    }

    render() {
        return (
            <OverlayView
                position={this.props.coordinates}
                mapPaneName={OverlayView.OVERLAY_MOUSE_TARGET}
                getPixelPositionOffset={getPixelPositionOffset}>
                <div 
                    onClick={this.handleMarkerClick.bind(this)}
                    style={{ background: `white`, border: `2px solid #111`, borderRadius:`50%`, cursor: `pointer` }}>
                    <img 
                        key={'img' + this.props.id}
                        style={{ borderRadius: `50%` }}
                        src={this.props.user.profile_image_url} />
                    {this.state.isOpen && 
                        <InfoWindow 
                            onCloseClick={this.handleMarkerClick}
                            position={this.props.coordinates}
                            zIndex={-1000}
                            key={'inf'+this.props.id}>
                            <span><b>@{this.props.user.screen_name}: </b>{this.props.text}</span>
                        </InfoWindow>}
                </div>
            </OverlayView>
        );
    }
}
