import React from "react";
import { withRouter } from "react-router";

//components
import ResetPassword from "../../../container/organisms/ResetPassword/index";
import Div from "../../../presentational/atoms/Div";
import LanguageChangeButton from "../../../presentational/molecules/LanguageChangeButton";

//css
import "./style.css";

//i18

//===================================================
// Component
//===================================================
class ForgetPassword extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    return (
      <Div className="template-forgot-password-wrapper">
        <LanguageChangeButton />
        <ResetPassword loginFieldErrors={this.props.loginFieldErrors} />
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

//===================================================
// Export
//===================================================
export default withRouter(ForgetPassword);
