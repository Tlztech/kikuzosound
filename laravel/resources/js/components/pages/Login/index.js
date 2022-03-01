import React from "react";

// Components
import Login from "../../container/templates/Login";
import Div from "../../presentational/atoms/Div";
import Toast from "../../presentational/molecules/Toast";

//css
import "./style.css";

// redux
import { connect } from "react-redux";

//i18next
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================

class LoginPage extends React.Component {
  componentDidMount() {
    sessionStorage.getItem("CSRF_TOKEN") ? this.props.history.push("/") : "";
  }
  render() {
    const { response_msg, t } = this.props;

    return (
      <Div className="login-wrapper">
        {/* {document.referrer && document.referrer.includes("reset-password") && ( */}
        {history.state.from == "success_reset" && (
          <Div className="organism-toast-success-wrapper">
            <Toast
              message={{
                mode: "success",
                content: t("reset_password:Success! password reset sent"),
              }}
            />
          </Div>
        )}
        <Login
          history={this.props.history}
          loginFieldErrors={this.props.loginFieldErrors}
        />
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
    LoginPage: state.LoginPage,
    // isResetPasswordSuccess : state.resetPassowrd.isSuccess,
    response_msg: state.resetPassword.response_msg,
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
)(withTranslation("reset_password")(LoginPage));
