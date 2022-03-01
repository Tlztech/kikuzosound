import React from "react";

// libs
import { Row, Col, Modal } from "react-bootstrap";
import InnerHTML from "dangerously-set-inner-html";

// components
import Label from "../../../presentational/atoms/Label";
import Image from "../../../presentational/atoms/Image";
import MediaComponent from "../../../presentational/molecules/Media";

// Images
import CrossIcon from "../../../../assets/CrossIcon.png";

//css
import "./style.css";

//i18
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================

class PalpationPreview extends React.Component {
  render() {
    const { isVisible, onHidePalpationPreview, previewItem, t } = this.props;
    const {
      sound_file,
      public_private,
      title_en,
      title,
      soundtype,
      area,
      normal_abnormal,
      status,
      video_file,
      is_video,
      selected_exam_group,
      description,
      description_en,
    } = previewItem;
    console.log(previewItem)
    return (
      <Modal
        className="organism-palpationPreview-wrapper"
        show={isVisible}
        onHide={() => onHidePalpationPreview && onHidePalpationPreview()}
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
      >
        <Modal.Header>
          <Label>{t("preview")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => onHidePalpationPreview && onHidePalpationPreview()}
          />
        </Modal.Header>
        <Modal.Body>
          <Row className="mb-3">
            <Col xs={4} className="organism-audio-label">
              <Label className="organism-labelTitle">{t("sound_source")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              {sound_file ? (
              <MediaComponent file={sound_file || "-"} type="sound" hash="" />
              ): (
                <Label>-</Label>
              )}
            </Col>
          </Row>

          {sound_file && sound_file.includes("mp4") && (
            <Row className="mb-3">
              <Col xs={4}>
                <Label className="organism-labelTitle">{t("video")}</Label>
              </Col>
              <Col xs={7} className="ml-2 pr-0">
                <Label>{is_video ? t("release") : t("private")}</Label>
              </Col>
            </Row>
          )}
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="labelTitle">{t("video_file")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
            {video_file ? (
              <MediaComponent
                height="180"
                width="240"
                className="videotest"
                file={video_file || "-"}
                type="video"
              />
            ): (
              <Label>-</Label>
            )}
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">{t("title_jp")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{title}</Label>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">{t("title_en")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{title_en}</Label>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("description_jp")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <InnerHTML html={description}/>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("description_en")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <InnerHTML html={description_en}/>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("normal_abnormal")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{t(normal_abnormal)}</Label>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">{t("status")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>
                {status == 2 || status == 3 ? t("public") : t("private")}
              </Label>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">{t("group_attr")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              {selected_exam_group &&
                selected_exam_group.map((item) => {
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

export default withTranslation("translation")(PalpationPreview);
