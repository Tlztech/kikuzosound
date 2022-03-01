import React from "react";

// bootstrap
import { Col, Row, Container } from "react-bootstrap";

//components
import Div from "../../../presentational/atoms/Div";
import P from "../../../presentational/atoms/P";
import Button from "../../../presentational/atoms/Button";
import LoginField from "../../../presentational/molecules/LoginField";
import { isEmail, isPasswordValid } from "../../../../common/Validation";
import {
  loginUser,
  setError,
} from "../../../../redux/modules/actions/LoginAction";

// redux
import { connect } from "react-redux";

//css
import "./style.css";

//i18next
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
class LoginForm extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      md_center: { span: 8, offset: 2 },
      xs_center: { span: 12, offset: 0 },
      loginFieldErrors: {
        invalid_email: "",
        invalid_password: "",
      },
    };
  }

  componentDidMount() {
    this.props.setError();
  }

  render() {
    const { loginFieldErrors } = this.state;
    const { history, t, auth, onForgotClicked } = this.props;
    const credentialsError = auth.loginFailedMessage;
    return (
      <Container className="organism-LoginForm-wrapper">
        <Row>
          <Col>
            <LoginField
              t={t}
              setInputEmailRef={(inpEmail) => (this.inpEmail = inpEmail)}
              setInputPasswordRef={(inpPassword) =>
                (this.inpPassword = inpPassword)
              }
              onChange={(e) => onChange(e, this)}
              loginFieldErrors={loginFieldErrors}
              credentialsError={credentialsError}
            />
            <P className="login-failed-error-message">
              {auth && t(credentialsError)}
            </P>
            <Div className="mt-4 molecules-LoginButton-wrapper">
              <Button mode="active" onClick={() => login(this, history)}>
                {t("login")}
              </Button>
            </Div>
            <P onClick={onForgotClicked}>{t("forgot_your_password")}</P>
          </Col>
        </Row>
      </Container>
    );
  }
}

//===================================================
// Functions
//===================================================

/**
 *
 * @param {*} context
 * @param history history
 */
const login = async (context, history) => {
  const isEmailValidated = isEmail(context.state.login_email);
  const { loginFieldErrors } = context.state;
  if (isEmailValidated.isValid) {
    loginFieldErrors.invalid_email = "";
    const isPasswordValidated = isPasswordValid(context.state.login_password);
    if (!isPasswordValidated.isValid) {
      context.inpPassword.focus();
      loginFieldErrors.invalid_password = isPasswordValidated.message;
      context.setState({ loginFieldErrors });
      return;
    }
    loginFieldErrors.invalid_password = "";

    //login
    await context.props.loginUser(
      context.state.login_email,
      context.state.login_password
    );
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
  loginFieldErrors.invalid_password = "";
  c.setState({ [e.target.name]: e.target.value, loginFieldErrors });
};

//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================
const mapStateToProps = (state) => {
  return {
    auth: state.auth,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, { loginUser, setError })(
  withTranslation("translation")(LoginForm)
);
