import React from "react";
import { connect } from "react-redux";

// libs
import { Row, Col, Modal } from "react-bootstrap";

// Components
import Button from "../../../presentational/atoms/Button";
import Label from "../../../presentational/atoms/Label";
import Image from "../../../presentational/atoms/Image";

//redux
import { logout } from "../../../../redux/modules/actions/LoginAction";

// Images
import CrossIcon from "../../../../assets/CrossIcon.png";

// css
import "./style.css";

//i18n
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================

class LogoutModal extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      isVisible: this.props.isVisible,
    };
  }

  componentDidUpdate(prevProps, prevState) {
    if (this.props.isVisible !== prevProps.isVisible) {
      this.setState({
        isVisible: this.props.isVisible,
      });
    }
  }

  render() {
    const { isVisible } = this.state;
    const { t } = this.props;
    return (
      <Modal
        centered
        show={isVisible}
        backdrop="static"
        onClose={() => {}}
        backdrop={true}
        className="organism-LogoutModal"
        onHide={() => onHideLogoutModal(this)}
        aria-labelledby="contained-modal-title-vcenter"
      >
        <Modal.Header>
          <Label>{t("logout")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => onHideLogoutModal(this)}
          />
        </Modal.Header>
        <Modal.Body>
          <Row>
            <Col className="text-center">
              <Label>{t("logout_text")}</Label>
            </Col>
          </Row>
        </Modal.Body>
        <Modal.Footer>
          <Row className="mx-auto">
            <Col md={6} xs={6} className="pr-0">
              <Button
                mode="active"
                onClick={() => handleLogout(this)}
                className="btn-block m-2 text-center organism-modal-button"
              >
                OK
              </Button>
            </Col>
            <Col md={6} xs={6} className="pl-0">
              <Button
                mode="cancel"
                onClick={() => onHideLogoutModal(this)}
                className="btn-block m-2 text-center organism-modal-button"
              >
                {t("cancel")}
              </Button>
            </Col>
          </Row>
        </Modal.Footer>
      </Modal>
    );
  }
}

//===================================================
// functions
//===================================================

/**
 * close logout modal
 * @param {*} context
 */
const onHideLogoutModal = (context) => {
  context.props.toggleVisible();
};

/**
 * logout and go to login
 * @param {*} context
 */
const handleLogout = (context) => {
  context.props.logout(context.props.userToken);
};

//===================================================
// actions
//===================================================

//===================================================
// redux
//===================================================
const mapStateToProps = (state) => {
  return {
    userToken: state.auth.userInfo && state.auth.userInfo.authorization,
    loggedInUser: state.auth.userInfo,
  };
};

//===================================================
// export
//===================================================
export default connect(mapStateToProps, { logout })(
  withTranslation("translation")(LogoutModal)
);
