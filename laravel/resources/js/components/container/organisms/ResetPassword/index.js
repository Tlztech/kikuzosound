import React from "react";
import { withRouter } from "react-router-dom";

// libs
import { Modal } from "react-bootstrap";
import SweetAlert from "react-bootstrap-sweetalert";

//components
import Div from "../../../presentational/atoms/Div";
import P from "../../../presentational/atoms/P";
import Label from "../../../presentational/atoms/Label";
import Button from "../../../presentational/atoms/Button";
import InputEmail from "../../../presentational/molecules/InputEmail";
import Toast from "../../../presentational/molecules/Toast";
import H2 from "../../../presentational/atoms/H2";
import BR from "../../../presentational/atoms/Br";

// common
import { isEmail } from "../../../../common/Validation";

// redux
import { connect } from "react-redux";
import { resetPassword } from "../../../../redux/modules/actions/ResetPasswordAction";

// Images
import CloseIcon from "@material-ui/icons/Close";

//css
import "./style.css";

//i18
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
class ResetPassword extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      isResetSuccess: false,
      isEmailNotFound: false,
      loginFieldErrors: {
        invalid_email: null,
      },
    };
  }

  render() {
    const { loginFieldErrors, isEmailNotFound, isResetSuccess } = this.state;
    const { t, isVisible } = this.props;
    return (
      <>
        <Modal
          centered
          show={isVisible}
          backdrop="static"
          onClose={() => {}}
          className="organism-ResetPassword-container"
          onHide={() => onCloseModal(this)}
          aria-labelledby="contained-modal-title-vcenter"
        >
          <Modal.Header>
            <Label>{t("translation:reset_password")}</Label>
            <CloseIcon
              onClick={() => onCloseModal(this)}
              fontSize="medium"
              htmlColor="#ffffff"
            />
          </Modal.Header>
          <Modal.Body>
            <InputEmail
              setInputEmailRef={(inpEmail) => (this.inpEmail = inpEmail)}
              name="reset_pwd"
              label={t("mail_address")}
              loginFieldErrors={loginFieldErrors}
              placeholder={t("mail_address_placeholder")}
              onChange={(e) => onChange(e, this)}
            />
            {loginFieldErrors.invalid_email && (
              <Label mode="error">
              </Label>
            )}
          </Modal.Body>
          <Modal.Footer>
            <Button
              mode="active"
              className=""
              onClick={() => submitEmail(this)}
            >
              {t("send_reset_link")}
            </Button>
          </Modal.Footer>
        </Modal>
        {isEmailNotFound && (
          <Div className="organism-toast-error-wrapper">
            <Toast message={{ mode: "error", content: "no_email_found" }} />
          </Div>
        )}
        <SweetAlert
          title=""
          show={isResetSuccess}
          onConfirm={() => this.setState({ isResetSuccess: false })}
          confirmBtnCssClass="sweet-alert-button"
        >
          <Div className="sweet-alert-content">
            <H2>
              {t("h2")} <BR /> {this.state.reset_pwd}
            </H2>
            <P>
              {t("p1")}
              <BR />
              {t("p2")}
            </P>
          </Div>
        </SweetAlert>
      </>
    );
  }
}

//===================================================
// Functions
//===================================================
/**
 * submit email
 * @param {*} context
 */
const submitEmail = async (context) => {
  const isEmailValidated = isEmail(context.state.reset_pwd);
  const { loginFieldErrors } = context.state;
  if (isEmailValidated.isValid) {
    loginFieldErrors.invalid_email = "";
    context.setState({ loginFieldErrors });
    await context.props.resetPassword(context.state.reset_pwd);
    if (context.props.isResetPassword.isSuccess) {
      context.setState({
        isResetSuccess: true,
      });
      context.props.onCloseModal();
    } else {
      context.setState(
        {
          isEmailNotFound: true,
        },
        () =>
          setTimeout(() => {
            context.setState({ isEmailNotFound: false });
          }, 5000)
      );
    }
    return;
  }
  context.inpEmail.focus();
  loginFieldErrors.invalid_email = isEmailValidated.message;
  context.setState({ loginFieldErrors });
};

/**
 * onChange
 * @param context c
 * @param event e
 */
const onChange = (e, c) => {
  const { loginFieldErrors } = c.state;
  loginFieldErrors.invalid_email = "";
  c.setState({ [e.target.name]: e.target.value, loginFieldErrors });
};

/**
 * close modal
 * @param {*} context
 */
const onCloseModal = (context) => {
  context.setState({
    loginFieldErrors: {
      invalid_email: null,
    },
  });
  context.props.onCloseModal();
};

//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================
const mapStateToProps = (state) => {
  return {
    isResetPassword: state.resetPassword,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, { resetPassword })(
  withRouter(withTranslation("reset_password", "translation")(ResetPassword))
);
