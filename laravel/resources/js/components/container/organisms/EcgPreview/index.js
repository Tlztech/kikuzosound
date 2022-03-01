import React from "react";

// libs
import { Row, Col, Modal } from "react-bootstrap";
import InnerHTML from "dangerously-set-inner-html";

// Components
import Label from "../../../presentational/atoms/Label";
import Image from "../../../presentational/atoms/Image";
import MediaComponent from "../../../presentational/molecules/Media/index";

// Images
import CrossIcon from "../../../../assets/CrossIcon.png";

// css
import "./style.css";

//i18
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
class EcgPreview extends React.Component {
  render() {
    const { isVisible, onHideEcgPreview, previewItem, t } = this.props;
    return (
      <Modal
        className="organism-EcgPreview-wrapper"
        show={isVisible}
        onHide={onHideEcgPreview && onHideEcgPreview}
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
      >
        <Modal.Header>
          <Label>{t("preview")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={onHideEcgPreview && onHideEcgPreview}
          />
        </Modal.Header>
        <Modal.Body>
          <Row className="mb-3">
            <Col xs={4} className="organism-audio-label">
              <Label className="organism-labelTitle">{t("sound_source")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <MediaComponent
                file={previewItem.sound_path}
                type="sound"
                hash=""
              />
            </Col>
          </Row>
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
              <Label>{t(previewItem.normal_abnormal)}</Label>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("ecg_explaination_jp")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <InnerHTML html={previewItem.ecg_explanation}/>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("ecg_explaination_en")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <InnerHTML html={previewItem.ecg_explanation_en}/>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">{t("status")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{t(previewItem.public_private)}</Label>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">{t("group_attr")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              {previewItem.selected_exam_group &&
                previewItem.selected_exam_group.map((item) => {
                  return (
                    <Label key={item.id} className="groupLabel">
                      {item.text}
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
export default withTranslation("translation")(EcgPreview);
