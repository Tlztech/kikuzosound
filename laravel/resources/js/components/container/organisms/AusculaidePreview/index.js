import React from "react";

// libs
import { Row, Col, Modal } from "react-bootstrap";
import InnerHTML from "dangerously-set-inner-html";

// Components
import Label from "../../../presentational/atoms/Label";
import P from "../../../presentational/atoms/P";
import Image from "../../../presentational/atoms/Image";
import Div from "../../../presentational/atoms/Div";
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
class AusculaidePreview extends React.Component {
  render() {
    const { isVisible, onHideAusculaidePreview, previewItem, t } = this.props;
    return (
      <Modal
        className="organism-AusculaidePreview-wrapper"
        show={isVisible}
        onHide={onHideAusculaidePreview && onHideAusculaidePreview}
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
      >
        <Modal.Header>
          <Label>{t("preview")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={onHideAusculaidePreview && onHideAusculaidePreview}
          />
        </Modal.Header>
        <Modal.Body>
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
                {t("descriptions_jp")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <InnerHTML html={previewItem.description}/>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("descriptions_en")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <InnerHTML html={previewItem.description_en}/>
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
                {t("explanatory_image_ja")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              {previewItem &&
                previewItem.explanatory_image.map((img_jp, index) => (
                  <Image
                    key={index}
                    url={`${img_jp.image_path}`}
                    className="organism-explanation-sound-image"
                  />
                ))}
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("explanatory_image_en")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              {previewItem &&
                previewItem.explanatory_image_en.map((img_en, index) => (
                  <Image
                    key={index}
                    url={`${img_en.image_path}`}
                    className="organism-explanation-sound-image"
                  />
                ))}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("body_portrait_front")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Image
                url={`${previewItem.body_image}`}
                className="organism-explanation-sound-image"
              />
            </Col>
          </Row>

          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("body_portrait_back")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Image
                url={`${previewItem.body_image_back}`}
                className="organism-explanation-sound-image"
              />
            </Col>
          </Row>

          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("image_description_ja")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <InnerHTML html={previewItem.image_description}/>
            </Col>
          </Row>

          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("image_description_en")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <InnerHTML html={previewItem.image_description_en}/>
            </Col>
          </Row>

          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("configuration")}
              </Label>
            </Col>

            <Col xs={7} className="ml-2 pr-0">
              <Row>
                <Label className="organism-labelTitle">A</Label>
                <Div className="organism-sound-point">
                  <P>{checkData(previewItem.a_x) + "(px)"}</P>
                  <P>{checkData(previewItem.a_y) + "(px)"}</P>
                  <P>{checkData(previewItem.a_r) + "(px)"}</P>
                </Div>
              </Row>
              <Div className="ml-4">
                <Row>
                  <Label className="organism-labelTitle">{t("Heart")}</Label>
                  <MediaComponent
                    file={`${previewItem.a_sound_file || "-"}`}
                    type="sound"
                  />
                </Row>
              </Div>
              <Div className="ml-4">
                <Row>
                  <Label className="organism-labelTitle">{t("Pulse")}</Label>
                  <MediaComponent
                    file={`${previewItem.pa_sound_file || "-"}`}
                    type="sound"
                  />
                </Row>
              </Div>
            </Col>
          </Row>

          <Row className="mb-3">
            <Col xs={4} />
            <Col xs={7} className="ml-2 pr-0">
              <Row>
                <Label className="organism-labelTitle">P</Label>
                <Div className="organism-sound-point">
                  <P>{checkData(previewItem.p_x) + "(px)"}</P>
                  <P>{checkData(previewItem.p_y) + "(px)"}</P>
                  <P>{checkData(previewItem.p_r) + "(px)"}</P>
                </Div>
              </Row>

              <Div className="ml-4">
                <Row>
                  <Label className="organism-labelTitle">{t("Heart")}</Label>
                  <MediaComponent
                    file={`${previewItem.p_sound_file || "-"}`}
                    type="sound"
                  />
                </Row>
              </Div>
              <Div className="ml-4">
                <Row>
                  <Label className="organism-labelTitle">{t("Pulse")}</Label>
                  <MediaComponent
                    file={`${previewItem.pp_sound_file || "-"}`}
                    type="sound"
                  />
                </Row>
              </Div>
            </Col>
          </Row>

          <Row className="mb-3">
            <Col xs={4} />

            <Col xs={7} className="ml-2 pr-0">
              <Row>
                <Label className="organism-labelTitle">T</Label>
                <Div className="organism-sound-point">
                  <P>{checkData(previewItem.t_x) + "(px)"}</P>
                  <P>{checkData(previewItem.t_y) + "(px)"}</P>
                  <P>{checkData(previewItem.t_r) + "(px)"}</P>
                </Div>
              </Row>
              <Div className="ml-4">
                <Row>
                  <Label className="organism-labelTitle">{t("Heart")}</Label>
                  <MediaComponent
                    file={`${previewItem.t_sound_file || "-"}`}
                    type="sound"
                  />
                </Row>
              </Div>
              <Div className="ml-4">
                <Row>
                  <Label className="organism-labelTitle">{t("Pulse")}</Label>
                  <MediaComponent
                    file={`${previewItem.pt_sound_file || "-"}`}
                    type="sound"
                  />
                </Row>
              </Div>
            </Col>
          </Row>

          <Row className="mb-3">
            <Col xs={4} />

            <Col xs={7} className="ml-2 pr-0">
              <Row>
                <Label className="organism-labelTitle">M</Label>
                <Div className="organism-sound-point">
                  <P>{checkData(previewItem.m_x) + "(px)"}</P>
                  <P>{checkData(previewItem.m_y) + "(px)"}</P>
                  <P>{checkData(previewItem.m_r) + "(px)"}</P>
                </Div>
              </Row>
              <Div className="ml-4">
                <Row>
                  <Label className="organism-labelTitle">{t("Heart")}</Label>
                  <MediaComponent
                    file={`${previewItem.m_sound_file || "-"}`}
                    type="sound"
                  />
                </Row>
              </Div>
              <Div className="ml-4">
                <Row>
                  <Label className="organism-labelTitle">{t("Pulse")}</Label>
                  <MediaComponent
                    file={`${previewItem.pm_sound_file || "-"}`}
                    type="sound"
                  />
                </Row>
              </Div>
            </Col>
          </Row>
          {/* Heart sounds */}
          <Row className="mb-3">
            <Col xs={4} />

            <Col xs={7} className="ml-2 pr-0">
              <Row>
                <Label className="organism-labelTitle">
                  {t("Heart sounds")}
                </Label>
              </Row>
              {Array.from(Array(4).keys()).map(item => {
                return (
                  <Div key={item + 1} className="ml-4">
                    <Row>
                      <Div className="organism-sound-point">
                        <P>
                          {checkData(previewItem[`h${item + 1}_x`]) + "(px)"}
                        </P>
                        <P>
                          {checkData(previewItem[`h${item + 1}_y`]) + "(px)"}
                        </P>
                        <P>
                          {checkData(previewItem[`h${item + 1}_r`]) + "(px)"}
                        </P>
                      </Div>
                      <MediaComponent
                        file={previewItem[`h${item + 1}_sound_file`] || "-"}
                        type="sound"
                      />
                    </Row>
                  </Div>
                );
              })}
            </Col>
          </Row>
          {/* Tracheal breath sounds */}
          <Row className="mb-3">
            <Col xs={4} />

            <Col xs={7} className="ml-2 pr-0">
              <Row>
                <Label className="organism-labelTitle">
                  {t("Tracheal breath sounds")}
                </Label>
              </Row>
              <Div className="ml-4">
                <Row>
                  <Div className="organism-sound-point">
                    <P>{checkData(previewItem.tr1_x) + "(px)"}</P>
                    <P>{checkData(previewItem.tr1_y) + "(px)"}</P>
                    <P>{checkData(previewItem.tr1_r) + "(px)"}</P>
                  </Div>
                  <MediaComponent
                    file={previewItem.tr1_sound_file || "-"}
                    type="sound"
                  />
                </Row>
              </Div>
              <Div className="ml-4">
                <Row>
                  <Div className="organism-sound-point">
                    <P>{checkData(previewItem.tr2_x) + "(px)"}</P>
                    <P>{checkData(previewItem.tr2_y) + "(px)"}</P>
                    <P>{checkData(previewItem.tr2_r) + "(px)"}</P>
                  </Div>
                  <MediaComponent
                    file={previewItem.tr2_sound_file || "-"}
                    type="sound"
                  />
                </Row>
              </Div>
            </Col>
          </Row>
          {/* Bronchail front start */}
          <Row className="mb-3">
            <Col xs={4} />

            <Col xs={7} className="ml-2 pr-0">
              <Row>
                <Label className="organism-labelTitle">{t("front")}</Label>
              </Row>
              <Div className="ml-4">
                <Row>
                  <Div className="organism-sound-point">
                    <P>{checkData(previewItem.br1_x) + "(px)"}</P>
                    <P>{checkData(previewItem.br1_y) + "(px)"}</P>
                    <P>{checkData(previewItem.br1_r) + "(px)"}</P>
                  </Div>
                  <MediaComponent
                    file={previewItem.br1_sound_file || "-"}
                    type="sound"
                  />
                </Row>
              </Div>
              <Div className="ml-4">
                <Row>
                  <Div className="organism-sound-point">
                    <P>{checkData(previewItem.br2_x) + "(px)"}</P>
                    <P>{checkData(previewItem.br2_y) + "(px)"}</P>
                    <P>{checkData(previewItem.br2_r) + "(px)"}</P>
                  </Div>
                  <MediaComponent
                    file={previewItem.br2_sound_file || "-"}
                    type="sound"
                  />
                </Row>
              </Div>
            </Col>
          </Row>
          {/* Bronchail back start */}
          <Row className="mb-3">
            <Col xs={4} />

            <Col xs={7} className="ml-2 pr-0">
              <Row>
                <Label className="organism-labelTitle">{t("back")}</Label>
              </Row>
              <Div className="ml-4">
                <Row>
                  <Div className="organism-sound-point">
                    <P>{checkData(previewItem.br3_x) + "(px)"}</P>
                    <P>{checkData(previewItem.br3_x) + "(px)"}</P>
                    <P>{checkData(previewItem.br3_r) + "(px)"}</P>
                  </Div>
                  <MediaComponent
                    file={previewItem.br3_sound_file || "-"}
                    type="sound"
                  />
                </Row>
              </Div>
              <Div className="ml-4">
                <Row>
                  <Div className="organism-sound-point">
                    <P>{checkData(previewItem.br4_x) + "(px)"}</P>
                    <P>{checkData(previewItem.br4_y) + "(px)"}</P>
                    <P>{checkData(previewItem.br4_r) + "(px)"}</P>
                  </Div>
                  <MediaComponent
                    file={previewItem.br4_sound_file || "-"}
                    type="sound"
                  />
                </Row>
              </Div>
            </Col>
          </Row>
          {/* Alveolar breath sounds */}
          <Row className="mb-3">
            <Col xs={4} />
            <Col xs={7} className="ml-2 pr-0">
              <Row>
                <Label className="organism-labelTitle">
                  {t("Alveolar breath sounds")}
                </Label>
              </Row>
              {Array.from(Array(12).keys()).map(item => {
                return (
                  <Div key={item + 1} className="ml-4">
                    <Label className="organism-labelTitle">
                      {(item + 1 == 1 && `(${t("front")})`) ||
                        (item + 1 == 7 && `(${t("back")})`)}
                    </Label>
                    <Row>
                      <Div className="organism-sound-point">
                        <P>
                          {checkData(previewItem[`ve${item + 1}_x`]) + "(px)"}
                        </P>
                        <P>
                          {checkData(previewItem[`ve${item + 1}_y`]) + "(px)"}
                        </P>
                        <P>
                          {checkData(previewItem[`ve${item + 1}_r`]) + "(px)"}
                        </P>
                      </Div>
                      <MediaComponent
                        file={previewItem[`ve${item + 1}_sound_file`] || "-"}
                        type="sound"
                      />
                    </Row>
                  </Div>
                );
              })}
            </Col>
          </Row>

          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">
                {t("content_group")}
              </Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>
                {t(`${getContentGroup(previewItem.content_group)}`)}
              </Label>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">{t("sort")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{t(previewItem.sort)}</Label>
            </Col>
          </Row>
          <Row className="mb-3">
            <Col xs={4}>
              <Label className="organism-labelTitle">{t("coordinate")}</Label>
            </Col>
            <Col xs={7} className="ml-2 pr-0">
              <Label>{t(previewItem.coordinate)}</Label>
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
              {previewItem.selected_exam_group.map(item => {
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
/**
 * return item_no -> content group item
 * @param {*} item_no
 * @returns
 */
const getContentGroup = (item_no) => {
  switch (parseInt(item_no)) {
    case 1:
      return "lung_sound";
    case 2:
      return "heart_sound";
    default:
      return "-";
  }
};

/**
 * return px data to display
 * @param {*} value
 * @returns
 */
const checkData = value => {
  let data = value == "null" || value == "undefined" ? " " : value;
  return data;
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
export default withTranslation("translation")(AusculaidePreview);
