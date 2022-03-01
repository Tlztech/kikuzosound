import React from "react";

// libs
import { Row, Col, Modal } from "react-bootstrap";
import InnerHTML from "dangerously-set-inner-html";

// Components
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
class XrayPreview extends React.Component {
  render() {
    const { isVisible, onHideXrayPreview, previewItem, t } = this.props;
    return (
      <Modal
        className="organism-XrayPreview-wrapper"
        show={isVisible}
        onHide={onHideXrayPreview && onHideXrayPreview}
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
      >
        <Modal.Header>
          <Label>{t("preview")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={onHideXrayPreview && onHideXrayPreview}
          />
        </Modal.Header>
        <Modal.Body>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">{t("image_file")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Image
                url={`${previewItem.image_path}`}
                className="organism-explanation-sound-image"
              />
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">{t("title_jp")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{previewItem.title}</Label>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">{t("title_en")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{previewItem.title_en}</Label>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("normal_abnormal")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>
                {previewItem.isNormal ? t("normal") : t("abnormal")}
              </Label>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("xray_explanation_jp")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <InnerHTML html={previewItem.description}/>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("xray_explanation_en")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <InnerHTML html={previewItem.description_en}/>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">{t("status")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{previewItem.isPublic ? t("public") : t("private")}</Label>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">{t("group_attr")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              {previewItem.exam_groups &&
                previewItem.exam_groups.map((item) => {
                  return (
                    <Label className="groupLabel" key={item.id}>
                      {item.name}
                    </Label>
                  );
                })}
            </Col>
          </Row>
        </Modal.Body>
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
export default withTranslation("translation")(XrayPreview);
