import React from "react";

// libs
import { Row, Col, Modal } from "react-bootstrap";

// Components
import Button from "../../../presentational/atoms/Button";
import Label from "../../../presentational/atoms/Label";
import Image from "../../../presentational/atoms/Image";

// Images
import CrossIcon from "../../../../assets/CrossIcon.png";

// css
import "./style.css";

//i18
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================

class DeleteModal extends React.Component {
  render() {
    const { isVisible, onHideDeleteModal, onDeletePressed, t } = this.props;
    return (
      <Modal
        className="organism-DeleteModal"
        show={isVisible}
        onHide={onHideDeleteModal}
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
      >
        <Modal.Header>
          <Label>{t("delete_btn")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={onHideDeleteModal}
          />
        </Modal.Header>
        <Modal.Body>
          <Row>
            <Col className="text-center">
              <Label>{t("delete_confirm")}</Label>
            </Col>
          </Row>
        </Modal.Body>
        <Modal.Footer>
          <Row className="mx-auto">
            <Col md={6} xs={6} className="pr-0">
              <Button
                mode="active"
                onClick={onDeletePressed}
                className="btn-block m-2 text-center organism-modal-button"
              >
                OK
              </Button>
            </Col>
            <Col md={6} xs={6} className="pl-0">
              <Button
                mode="cancel"
                onClick={onHideDeleteModal}
                className="btn-block m-2 text-center organism-modal-button"
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
export default withTranslation("translation")(DeleteModal);
