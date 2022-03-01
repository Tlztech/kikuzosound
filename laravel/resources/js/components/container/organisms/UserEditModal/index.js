import React from "react";

// libs
import { Row, Col, Modal } from "react-bootstrap";

// Components
import Button from "../../../presentational/atoms/Button";
import Label from "../../../presentational/atoms/Label";
import Image from "../../../presentational/atoms/Image";
import RadioWithLabel from "../../../presentational/molecules/RadioWithLabel";

// Images
import CrossIcon from "../../../../assets/CrossIcon.png";

// css
import "./style.css";

//i18
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
class EditUserModal extends React.Component {
  render() {
    const {
      isVisible,
      onHideEditUserModal,
      t,
      is_enabled,
      onRadioClick,
      handleSaveClicked,
    } = this.props;
    return (
      <Modal
        className="organism-editUserModal-wrapper"
        show={isVisible}
        onHide={() => onHideEditUserModal && onHideEditUserModal()}
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
      >
        <Modal.Header>
          <Label>{t("edit_user")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => onHideEditUserModal()}
          />
        </Modal.Header>
        <Modal.Body>
          <Col>
            <Label>{t("user_status")}</Label>
            <RadioWithLabel
              title={t("active_button")}
              name={"is_enabled"}
              value={1}
              defaultChecked={is_enabled == 1 ? "checked" : ""}
              onClick={(event) => onRadioClick && onRadioClick(event)}
            />
            <RadioWithLabel
              title={t("disabled")}
              name={"is_enabled"}
              value={0}
              defaultChecked={is_enabled == 0 ? "checked" : ""}
              onClick={(event) => onRadioClick && onRadioClick(event)}
            />
          </Col>
        </Modal.Body>
        <Modal.Footer>
          <Row>
            <Col md={5} xs={5}>
              <Button
                mode="active"
                onClick={() => handleSaveClicked()}
                className="btn-block text-center organism-add-modal-button"
              >
                {t("save")}
              </Button>
            </Col>
            <Col md={4} xs={4}>
              <Button
                mode="cancel"
                onClick={() => onHideEditUserModal()}
                className="btn-block text-center organism-add-modal-button"
              >
                {t("cancel_btn")}
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

//===================================================
// actions
//===================================================

//===================================================
// redux
//===================================================

//===================================================
// export
//===================================================
export default withTranslation("translation")(EditUserModal);
