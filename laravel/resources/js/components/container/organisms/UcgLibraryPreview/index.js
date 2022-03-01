import React from "react";

// libs
import { Row, Col, Modal } from "react-bootstrap";
import InnerHTML from "dangerously-set-inner-html";

// Components
import Label from "../../../presentational/atoms/Label";
import Image from "../../../presentational/atoms/Image";
import MediaComponent from "../../../presentational/molecules/Media";

// Images
import CrossIcon from "../../../../assets/CrossIcon.png";

// css
import "./style.css";

//i18
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
class UcgLibraryPreview extends React.Component {
  render() {
    const { isVisible, onHideUcgPreview, previewItem, t } = this.props;
    const {
      video_file,
      title,
      title_en,
      normal_abnormal,
      ucg_explanation,
      ucg_explanation_en,
      public_private,
      selected_exam_group,
    } = previewItem;
    return (
      <Modal
        className="organism-ucgPreview-wrapper"
        show={isVisible}
        onHide={() => onHideUcgPreview && onHideUcgPreview()}
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
      >
        <Modal.Header>
          <Label>{t("preview")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => onHideUcgPreview && onHideUcgPreview()}
          />
        </Modal.Header>

        <Modal.Body>
          <Row className="mb-1">
            <Col xs={4}>
              <Label className="labelTitle">{t("video_file")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              {previewItem.video_file ? (
                <MediaComponent
                  height="180"
                  width="240"
                  className="videotest"
                  file={video_file}
                  type="video"
                  hash=""
                />
              ) : (
                <Label>-</Label>
              )}
            </Col>
          </Row>

          <Row className="mb-1">
            <Col xs={4}>
              <Label className="labelTitle">{t("title_jp")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{title}</Label>
            </Col>
          </Row>

          <Row className="mb-1">
            <Col xs={4}>
              <Label className="labelTitle">{t("title_en")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{title_en}</Label>
            </Col>
          </Row>

          <Row className="mb-1">
            <Col xs={4}>
              <Label className="labelTitle">{t("normal_abnormal")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{t(normal_abnormal)}</Label>
            </Col>
          </Row>

          <Row className="mb-1">
            <Col xs={4}>
              <Label className="labelTitle">{t("ucg_explanation_jp")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <InnerHTML html={ucg_explanation}/>
            </Col>
          </Row>

          <Row className="mb-1">
            <Col xs={4}>
              <Label className="labelTitle">{t("ucg_explanation_en")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <InnerHTML html={ucg_explanation_en}/>
            </Col>
          </Row>

          <Row className="mb-1">
            <Col xs={4}>
              <Label className="labelTitle">{t("public/private")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{t(public_private)}</Label>
            </Col>
          </Row>

          <Row className="mb-1">
            <Col xs={4}>
              <Label className="labelTitle">{t("group_attr")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              {selected_exam_group &&
                selected_exam_group.map((item) => {
                  return (
                    <Label className="groupLabel" key={item.id}>
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
export default withTranslation("translation")(UcgLibraryPreview);
