import React from "react";

// components
import BR from "../../atoms/Br";
import Div from "../../atoms/Div";
import Label from "../../atoms/Label";
import InputEmail from "../InputEmail";
import InputPassword from "../InputPassword";

// bootstrap
import { Row } from "react-bootstrap";

// redux
import { connect } from "react-redux";

// style
import "./style.css";

//i18next
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
class LoginField extends React.Component {
  render() {
    const {
      loginFieldErrors,
      onChange,
      setInputEmailRef,
      setInputPasswordRef,
      t,
      credentialsError,
    } = this.props;
    return (
      <Div className="molecules-LoginField-wrapper">
        <Row>
          <InputEmail
            setInputEmailRef={(inpEmail) =>
              setInputEmailRef && setInputEmailRef(inpEmail)
            }
            name="login_email"
            label={t("email_address")}
            placeholder={t("enter_email")}
            onChange={onChange}
            loginFieldErrors={loginFieldErrors}
            credentialsError={credentialsError}
          />
          {loginFieldErrors.invalid_email && (
            <Label mode="error">{t(loginFieldErrors.invalid_email)}</Label>
          )}
        </Row>
        <BR />
        <Row>
          <InputPassword
            setInputPasswordRef={(inpPassword) =>
              setInputPasswordRef && setInputPasswordRef(inpPassword)
            }
            name="login_password"
            label={t("password")}
            placeholder={t("enter_password")}
            onChange={onChange}
            loginFieldErrors={loginFieldErrors}
            credentialsError={credentialsError}
          />
          <Div className="loginFormErrors ml-1">
            {loginFieldErrors.invalid_password && (
              <Label mode="error">{t(loginFieldErrors.invalid_password)}</Label>
            )}
          </Div>
        </Row>
      </Div>
    );
  }
}

//===================================================
// Functions
//===================================================

//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================
const mapStateToProps = (state) => {
  return {
    LoginForm: state.LoginForm,
  };
};

const mapDispatchToProps = (dispatch) => {
  return {};
};

//===================================================
// Export
//===================================================
export default connect(
  mapStateToProps,
  mapDispatchToProps
)(withTranslation("translation")(LoginField));
