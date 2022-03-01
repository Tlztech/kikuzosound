import React from "react";

//components
import Div from "../../../presentational/atoms/Div";
import P from "../../../presentational/atoms/P";
import LanguageChangeButton from "../../../presentational/molecules/LanguageChangeButton";
import ResetPasswordFromMailForm from "../../organisms/ResetPasswordFromMailForm";
import { withTranslation } from "react-i18next";

//css
import "./style.css";

//===================================================
// Component
//===================================================
class ResetPasswordFromMail extends React.Component {
  constructor(props) {
    super(props);
  }

  componentDidMount() {
    console.log("navigation",performance.getEntriesByType("navigation"))
    // if (performance.getEntriesByType("navigation")[0].type == "reload")
    //   window.history.pushState({from : "reload"}, 'backtologin', `/login`);
  }

  render() {
    const { t } = this.props;

    return (
      <>
        <Div className="templates-reset-password-from-mail">
          <LanguageChangeButton />
          <P className="templates-univ-text">3SP - UNIV</P>
          <ResetPasswordFromMailForm />
        </Div>
      </>
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
export default withTranslation("reset_password")(ResetPasswordFromMail);
