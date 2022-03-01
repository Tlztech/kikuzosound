import React from "react";

// bootstrap
import { Col, Row } from "react-bootstrap";

//components
import Div from "../../../presentational/atoms/Div";
import P from "../../../presentational/atoms/P";
import LoginForm from "../../organisms/LoginForm";
import ResetPassword from "../../organisms/ResetPassword";
import LanguageChangeButton from "../../../presentational/molecules/LanguageChangeButton";

//css
import "./style.css";

// redux
import { connect } from "react-redux";
//i18next
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
class LoginTemplate extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      isForgotModalVisible: false,
    };
  }

  render() {
    const { isForgotModalVisible } = this.state;
    const { t } = this.props;
    return (
      <Div className="templates-Login-wrapper">
        <LanguageChangeButton />
        <P className="templates-univ-text">iPax admin</P>
        <P>{t('login_page_title')}</P>
        <Row>
          <Col>
            <LoginForm
              history={this.props.history}
              loginFieldErrors={this.props.loginFieldErrors}
              onForgotClicked={() => handleForgotModal(this)}
            />
          </Col>
        </Row>
        <ResetPassword
          isVisible={isForgotModalVisible}
          onCloseModal={() => handleForgotModal(this)}
        />
      </Div>
    );
  }
}

//===================================================
// Functions
//===================================================
/**
 * toggle forgot modal
 * @param {*} context
 */
const handleForgotModal = (context) => {
  context.setState({
    isForgotModalVisible: !context.state.isForgotModalVisible,
  });
};

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
export default connect(mapStateToProps, mapDispatchToProps)(
  withTranslation("translation")(LoginTemplate)
);