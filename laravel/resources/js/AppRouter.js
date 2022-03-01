import React from "react";
import {
  BrowserRouter as Router,
  Switch,
  Route,
  withRouter,
  Redirect,
} from "react-router-dom";
import { BroadcastChannel } from "broadcast-channel";

// bootstrap
import "bootstrap/dist/css/bootstrap.min.css";

// redux
import { connect } from "react-redux";

//pages
import {
  Login,
  Logout,
  ExamManagement,
  UserManagement,
  LogAnalytics,
  Home,
  QuizPacks,
  Quizzes,
  AsculaideLibrary,
  InspectionLibrary,
  XrayLibrary,
  EcgLibrary,
  StethoSounds,
  UcgLibrary,
  PalpationLibrary,
  ChoiceSelection,
  ExamAnalytics,
  ResetPasswordFromMail,
} from "./components/pages";

class AppRouter extends React.Component {
  componentDidMount() {
    handleLogoutAllTabs(this);
  }

  render() {
    const { auth } = this.props;
    if (!auth) {
      return (
        <Router basename="/group_admin">
          <Switch>
            <Route exact path="/login" component={Login} />
            <Route
              exact
              path="/reset-password"
              component={ResetPasswordFromMail}
            />
            <Redirect to="/login" />
          </Switch>
        </Router>
      );
    }
    return (
      <Router basename="/group_admin">
        <Switch>
          <Route exact path="/" component={Home} />
          <Route exact path="/analytics" component={ExamAnalytics} />
          <Route exact path="/log-analytics" component={LogAnalytics} />
          <Route exact path="/exam" component={ExamManagement} />
          <Route exact path="/user" component={UserManagement} />
          <Route exact path="/quiz-packs" component={QuizPacks} />
          <Route exact path="/quizzes" component={Quizzes} />
          <Route
            exact
            path="/ausculaide-library"
            component={AsculaideLibrary}
          />
          <Route
            exact
            path="/inspection-library"
            component={InspectionLibrary}
          />
          <Route exact path="/xray-library" component={XrayLibrary} />
          <Route exact path="/ecg-library" component={EcgLibrary} />
          <Route exact path="/stetho-sounds" component={StethoSounds} />
          <Route exact path="/ucg-library" component={UcgLibrary} />
          <Route exact path="/palpation-library" component={PalpationLibrary} />
          <Route exact path="/quiz-pack/:id" component={ChoiceSelection} />
          <Route exact path="/logout" component={Logout} />
          <Redirect to="/" />
        </Switch>
      </Router>
    );
  }
}

//===================================================
// Functions
//===================================================
/**
 * reload all open tabs
 */
const handleLogoutAllTabs = () => {
  const logoutChannel = new BroadcastChannel("logout");
  logoutChannel.onmessage = (event) => {
    if (event && event === "LOGOUT_SUCCESS") {
      window.location.reload();
      logoutChannel.close();
    }
  };
};

//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================
const mapStateToProps = (state) => {
  return {
    auth: state.auth.userInfo,
    state: state,
  };
};

//===================================================
//  Export
//===================================================

export default connect(mapStateToProps, null)(withRouter(AppRouter));
