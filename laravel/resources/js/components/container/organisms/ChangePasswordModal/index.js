import React from "react";

// libs
import { Col, Modal } from "react-bootstrap";
import { connect } from "react-redux";

// Components
import Button from "../../../presentational/atoms/Button";
import Label from "../../../presentational/atoms/Label";
import Image from "../../../presentational/atoms/Image";
import Span from "../../../presentational/atoms/Span";
import InputWithLabel from "../../../presentational/molecules/InputWithLabel";

// Images
import CrossIcon from "../../../../assets/CrossIcon.png";

// Redux
import { changePassword } from "../../../../redux/modules/actions/ChangePasswordAction";
import { logout } from "../../../../redux/modules/actions/LoginAction";

// css
import "./style.css";

//i18
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
const initial_state = {
  current_password: "",
  new_password: "",
  confirm_password: "",
  errors: {},
};
class ChangePasswordModal extends React.Component {
  constructor(props) {
    super(props);
    this.state = initial_state;
  }
  render() {
    const { isVisible, t } = this.props;
    const { errors } = this.state;
    const errorCollection = errors && Object.keys(errors);
    return (
      <Modal
        className="organism-changePasswordModal-wrapper"
        show={isVisible}
        onHide={() => closeModal(this)}
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
      >
        <Modal.Header>
          <Label>{t("change_password")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => closeModal(this)}
          />
        </Modal.Header>
        <Modal.Body>
          <Col>
            <InputWithLabel
              validateError={errorCollection.includes("current_password")}
              typeName={"password"}
              label={t("current_password")}
              placeholder={t("password")}
              name="current_password"
              onChange={(event) => handleChange(event, this)}
            />
            <Span className="error">{errors["current_password"]}</Span>

            <InputWithLabel
              validateError={errorCollection.includes("new_password")}
              typeName={"password"}
              label={t("new_password")}
              placeholder={t("password")}
              name="new_password"
              onChange={(event) => handleChange(event, this)}
            />
            <Span className="error">{errors["new_password"]}</Span>

            <InputWithLabel
              validateError={
                errorCollection.includes("confirm_password") ||
                errorCollection.includes("confirm_password_match")
              }
              typeName={"password"}
              label={t("confirm_new_password")}
              placeholder={t("password")}
              name="confirm_password"
              onChange={(event) => handleChange(event, this)}
            />
            <Span className="error">
              {errors["confirm_password"] ||
                errors["confirm_password_match"] ||
                errors["server_error"]}
            </Span>
          </Col>
        </Modal.Body>
        <Modal.Footer>
          <Button
            type="submit"
            onClick={() => handleSubmit(this)}
            mode="active"
            className="btn-block text-center organism-add-modal-button"
          >
            {t("save")}
          </Button>
        </Modal.Footer>
      </Modal>
    );
  }
}

//===================================================
// functions
//===================================================

const closeModal = (context) => {
  const { onHideChangePasswordModal } = context.props;
  context.setState({
    ...initial_state,
  });
  onHideChangePasswordModal && onHideChangePasswordModal();
};

/**
 * handle input change
 * @param {event} event
 * @param {*} context
 */
const handleChange = (event, context) => {
  context.setState({ [event.target.name]: event.target.value });
};

/**
 * handle validation
 * @param {*} context
 */
const handleValidation = (context) => {
  const { current_password, new_password, confirm_password } = context.state;
  const { t } = context.props;
  let errors = {};
  let formIsValid = true;
  errors["server_error"] = null;
  //current_password
  if (!current_password || current_password.trim.length < 0) {
    formIsValid = false;
    errors["current_password"] = t("validate_current_password");
  }
  //new_password
  if (!new_password || new_password.trim.length < 0) {
    formIsValid = false;
    errors["new_password"] = t("validate_new_password");
  }
  //confirm_password
  if (!confirm_password || confirm_password.trim.length < 0) {
    formIsValid = false;
    errors["confirm_password"] = t("validate_confirm_password");
  }

  if (confirm_password && new_password && confirm_password !== new_password) {
    formIsValid = false;
    errors["confirm_password_match"] = t("password_match");
  }
  context.setState({ errors: errors });
  return formIsValid;
};

/**
 * handle submit
 * @param {*} context
 */
const handleSubmit = async (context) => {
  const isSubmitOk = handleValidation(context);
  if (isSubmitOk) {
    const { changePassword, userToken, userEmail, t } = context.props;
    const { current_password, new_password, confirm_password } = context.state;
    await changePassword(
      { current_password, new_password, confirm_password, userEmail },
      userToken
    );
    if (context.props.isChangePasswordSuccess) {
      // context.props.logout(userToken);
      closeModal(context);
    } else {
      let errors = {};
      errors["server_error"] = t(context.props.changePasswordMessage);
      context.setState({ errors: errors });
    }
  }
};
//===================================================
// actions
//===================================================

//===================================================
// redux
//===================================================
const mapStateToProps = (state) => {
  return {
    isChangePasswordSuccess: state.changePassword.isSuccess,
    changePasswordMessage: state.changePassword.message,
    userEmail: state.auth.userInfo && state.auth.userInfo.user.email,
    userToken: state.auth.userInfo && state.auth.userInfo.authorization,
  };
};

//===================================================
// export
//===================================================
export default connect(mapStateToProps, { changePassword, logout })(
  withTranslation("translation")(ChangePasswordModal)
);
