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
class InspectionPreview extends React.Component {
  render() {
    const { isVisible, onHideInspectionPreview, previewItem, t } = this.props;
    console.log(previewItem);
    return (
      <Modal
        className="organism-inspectionPreview-wrapper"
        show={isVisible}
        onHide={() => onHideInspectionPreview && onHideInspectionPreview()}
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
      >
        <Modal.Header>
          <Label>{t("preview")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => onHideInspectionPreview && onHideInspectionPreview()}
          />
        </Modal.Header>
        <Modal.Body>
          <Row className="mb-1">
            <Col xs={4} className="organism-audio-label">
              <Label className="labelTitle">{t("sound_file")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
            {previewItem.item.sound_path ? (
              <MediaComponent
                file={`${previewItem.item.sound_path || "-"}`}
                type="sound"
              />
            ): (
              <Label>-</Label>
            )}
            </Col>
          </Row>

          {previewItem.sound_path && previewItem.sound_path.includes("mp4") && (
            <Row className="mb-3">
              <Col xs={4}>
                <Label className="labelTitle">{t("video")}</Label>
              </Col>
              <Col xs={7} className="ml-2 pr-0">
                <Label>
                  {previewItem.is_video_show ? t("release") : t("private")}
                </Label>
              </Col>
            </Row>
          )}

          <Row className="mb-1">
            <Col xs={4}>
              <Label className="labelTitle">{t("video_file")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
            {previewItem.item.sound_path ? (
              <MediaComponent
                height="180"
                width="240"
                className="videotest"
                file={`${previewItem.item.video_path || "-"}`}
                type="video"
              />
            ): (
              <Label>-</Label>
            )}
            </Col>
          </Row>
          <Row className="mb-1">
            <Col xs={4}>
              <Label className="labelTitle">{t("title_jp")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{previewItem.title}</Label>
            </Col>
          </Row>
          <Row className="mb-1">
            <Col xs={4}>
              <Label className="labelTitle">{t("title_en")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{previewItem.title_en}</Label>
            </Col>
          </Row>
          <Row className="mb-1">
            <Col xs={4}>
              <Label className="labelTitle">{t("normal_abnormal")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>
                {previewItem.is_normal == 1 ? t("normal") : t("abnormal")}
              </Label>
            </Col>
          </Row>
          <Row className="mb-1">
            <Col xs={4}>
              <Label className="labelTitle">{t("description_jp")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <InnerHTML html={previewItem.item.description}/>
            </Col>
          </Row>
          <Row className="mb-1">
            <Col xs={4}>
              <Label className="labelTitle">{t("description_en")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <InnerHTML html={previewItem.item.description_en}/>
            </Col>
          </Row>
          <Row className="mb-1">
            <Col xs={4}>
              <Label className="labelTitle">{t("status")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{t(getItemStatus(previewItem.status))}</Label>
            </Col>
          </Row>

          <Row className="mb-1">
            <Col xs={4}>
              <Label className="labelTitle">{t("group_attr")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              {previewItem.exam_groups && previewItem.exam_groups.map((item) => {
                return (
                  <Label key={item.id} className="groupLabel">
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

/**
 * Get library item status
 */
const getItemStatus = (status) => {
  switch (status) {
    case 0:
    case 1:
      return "private";
    case 2:
    case 3:
      return "public";
    default:
      return "";
  }
};

//===================================================
// actions
//===================================================

//===================================================
// redux
//===================================================

//===================================================
// export
//===================================================
export default withTranslation("translation")(InspectionPreview);
