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
class StethoPreview extends React.Component {
  render() {
    const { isVisible, onHideStethoPreview, previewItem, t } = this.props;
    const {
      sound_source,
      is_video,
      title,
      title_en,
      soundtype,
      ausculation_site,
      ausculation_site_en,
      normal_abnormal,
      image_list,
      image_list_en,
      status,
      disease,
      disease_en,
      description,
      description_en,
      source_desc,
      source_desc_en,
      conversion_type
    } = previewItem;
    console.log(previewItem)
    if (!previewItem) {
      return null;
    }
    return (
      <Modal
        className="organism-stethoPreview-wrapper"
        show={isVisible}
        onHide={() => onHideStethoPreview && onHideStethoPreview()}
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
      >
        <Modal.Header>
          <Label>{t("preview")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => onHideStethoPreview && onHideStethoPreview()}
          />
        </Modal.Header>
        <Modal.Body>
          <Row className="mb-3">
            <Col xs={4} className="organism-audio-label">
              <Label className="organism-labelTitle">{t("sound_source")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              {previewItem.sound_source ? (
                <MediaComponent file={sound_source} type="sound" hash="" />
              ) : (
                "-"
              )}
            </Col>
          </Row>
          {previewItem.sound_source && (
            <Row className="mb-3">
              <Col xs={4}>
                <Label className="organism-labelTitle">{t("video")}</Label>
              </Col>
              <Col xs={7} className="ml-2 pr-0">
                <Label>{is_video == 1 ? t("release") : t("private")}</Label>
              </Col>
            </Row>
          )}

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
                {t("ausculation_sound_type")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{t(soundtype)}</Label>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("ausculation_site_jp")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{ausculation_site}</Label>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("ausculation_site_en")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{ausculation_site_en}</Label>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("conversion_type")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{t(conversion_type)}</Label>
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
              <Label className="organism-labelTitle">
                {t("disease_jp")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{disease}</Label>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("disease_en")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{disease_en}</Label>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("sound_source_description_jp")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{source_desc}</Label>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("sound_source_description_en")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{source_desc_en}</Label>
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
                {t("explanation_of_sound")}(JP)
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              {image_list &&
                image_list.map((explainImg, index) => {
                  return (
                    <Image
                      key={index}
                      isPossibleError={false}
                      className="organism_sound_img"
                      url={`${explainImg.image_path}`}
                    />
                  );
                })}
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("explanation_of_sound")}(EN)
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              {image_list_en &&
                image_list_en.map((explainImg, index) => {
                  return (
                    <Image
                      key={index}
                      isPossibleError={false}
                      className="organism_sound_img"
                      url={`${explainImg.image_path}`}
                    />
                  );
                })}
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">{t("status")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>
                {status == 2 || previewItem.status == 3
                  ? t("public")
                  : t("private")}
              </Label>
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
export default withTranslation("translation")(StethoPreview);
