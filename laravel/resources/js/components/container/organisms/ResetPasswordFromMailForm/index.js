import React from "react";
import  { Redirect } from 'react-router-dom'

//components
import Div from "../../../presentational/atoms/Div";
import InputPassword from "../../../presentational/molecules/InputPassword";
import BR from "../../../presentational/atoms/Br";
import Button from "../../../presentational/atoms/Button";
import Label from "../../../presentational/atoms/Label";

//redux
import { connect } from "react-redux";
import { setNewPassword } from "../../../../redux/modules/actions/ResetPasswordAction";

//css
import "./style.css";

//i18next
import { withTranslation } from "react-i18next";

const initial_state = {
  password: "",
  confirm_password: "",
  errors: {},
};

//===================================================
// Component
//===================================================
class ResetPasswordFromMailForm extends React.Component {
  constructor(props) {
    super(props);
    this.state = initial_state;
  }

  render() {
    const { t } = this.props;
    const { errors } = this.state;
    return (
      <Div className="organism-ResetPasswordMail-wrapper">
        <InputPassword
          setInputPasswordRef={(inpPassword) =>
            (this.inpPassword = inpPassword)
          }
          name="password"
          label={t("password")}
          placeholder={t("password")}
          onChange={(e) => handleChange(e, this)}
          credentialsError={errors["password"]}
        />
        {errors["password"] && (
          <Label mode="error">{t(errors["password"])}</Label>
        )}
        <BR />
        <InputPassword
          setInputPasswordRef={(inpConfirmPassword) =>
            (this.inpConfirmPassword = inpConfirmPassword)
          }
          name="confirm_password"
          label={t("confirm_password")}
          placeholder={t("confirm_password")}
          onChange={(e) => handleChange(e, this)}
          credentialsError={
            errors["confirm_password"] || errors["confirm_password_match"]
          }
        />
        {(errors["confirm_password"] || errors["confirm_password_match"]) && (
          <Label mode="error">
            {t(
              errors["confirm_password"] ||
                errors["confirm_password_match"] ||
                errors["server_error"]
            )}
          </Label>
        )}
        <Div className="mt-4 organism-resetButton-wrapper">
          <Button mode="active" onClick={() => handleResetPassword(this)}>
            {t("reset_password")}
          </Button>
        </Div>
      </Div>
    );
  }
}

//===================================================
// Functions
//===================================================

/**
 * handle input change
 * @param {event} event
 * @param {*} context
 */
const handleChange = (event, context) => {
  context.setState({ [event.target.name]: event.target.value });
};

/**
 * validate password fields
 * @param {*} context
 * @returns
 */
const handleValidateData = (context) => {
  const { password, confirm_password } = context.state;
  let errors = {};
  let isFormValid = true;
  errors["server_error"] = null;
  if (!password || password.trim().length === 0) {
    isFormValid = false;
    errors["password"] = "password_error";
  }
  if (!confirm_password || confirm_password.trim().length === 0) {
    isFormValid = false;
    errors["confirm_password"] = "confirm_password_error";
  }
  if (password && confirm_password && confirm_password !== password) {
    isFormValid = false;
    errors["confirm_password_match"] = "password_match";
  }
  context.setState({ errors: errors });
  return isFormValid;
};

/**
 * Reset password on button click
 * @param {*} context
 */
const handleResetPassword = async (context) => {
  var token = window.location.search;
  let strtoken = token.replace("?", "");
  let user = {
    token: strtoken,
    password: context.state.password,
  };
  const isValidated = handleValidateData(context);
  if (isValidated) {
    await context.props.setNewPassword(user);
    window.history.pushState({from : "success_reset"}, 'backtologin_reset', `/group_admin/login`);
    location.href = '/group_admin/login';
  }
};

//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================

//===================================================
// Export
//===================================================
/**
 * redux state
 * @param {*} state
 */
const mapStateToProps = (state) => {
  return {
    response_status: state.resetPassword.isSuccess,
    response_msg: state.resetPassword.response_msg,
  };
};

export default connect(mapStateToProps, {
  setNewPassword,
})(withTranslation("translation")(ResetPasswordFromMailForm));
