import React from "react";

//components
import ForgotPassword from "../../container/templates/ForgetPassword";

//css
import "./style.css";

//===================================================
// Component
//===================================================

class ForgotPasswordPage extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { data } = this.props;
    return <ForgotPassword loginFieldErrors={this.props.loginFieldErrors} />;
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
export default ForgotPasswordPage;
