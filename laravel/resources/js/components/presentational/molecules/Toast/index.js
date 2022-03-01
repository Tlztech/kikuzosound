import React from "react";
// redux

// components
import Toast from "react-bootstrap/Toast";

// style
import "./style.css";

//Image
import CheckCircleOutlineIcon from "@material-ui/icons/CheckCircleOutline";
import ErrorOutlineIcon from "@material-ui/icons/ErrorOutline";
import WarningIcon from "@material-ui/icons/Warning";
import InfoIcon from "@material-ui/icons/Info";

//i18n
import { withTranslation } from "react-i18next";
//===================================================
// Component
//===================================================

class ToastComponent extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      show: this.props.message.content ? true : false,
    };
  }
  render() {
    const { show } = this.state;
    const { message, t } = this.props;
    return (
      <div>
        <Toast
          className={
            message.mode == "success"
              ? "toast-style toast-style-success"
              : message.mode == "error"
              ? "toast-style toast-style-error"
              : message.mode == "warning"
              ? "toast-style toast-style-warning"
              : "toast-style toast-style-info"
          }
          onClose={() => this.setState({ show: false })}
          show={show}
          delay={5000}
          autohide
          animation={true}
        >
          <Toast.Body className="toast-body-style">
            {message.mode == "success" ? (
              <CheckCircleOutlineIcon className="icon-style" />
            ) : message.mode == "error" ? (
              <ErrorOutlineIcon className="icon-style" />
            ) : message.mode == "warning" ? (
              <WarningIcon className="icon-style" />
            ) : (
              <InfoIcon className="icon-style" />
            )}
            <span>{t(message.content)}</span>
          </Toast.Body>
        </Toast>
      </div>
    );
  }
}

//===================================================
// Functions
//===================================================

//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================

//===================================================
// Export
//===================================================
export default withTranslation()(ToastComponent);
