import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Modal } from 'react-bootstrap';

class OverlayMessage extends Component {
  render() {
    return (
      <Modal show={this.props.showOverlay}>
        <Modal.Body>
          <h4>{this.props.overlayMessage}</h4>
        </Modal.Body>
      </Modal>
    )
  }
}

export default connect(
  (state) => ({ showOverlay: state.showOverlay, overlayMessage: state.overlayMessage })
)(OverlayMessage);
