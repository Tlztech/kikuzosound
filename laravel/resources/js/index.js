import React, { Component } from "react";
import ReactDOM from "react-dom";
import { BrowserRouter as Router, Route, Link, Switch } from "react-router-dom";
import { Provider } from "react-redux";
import store, { persistor } from "./redux/store";
import { PersistGate } from "redux-persist/integration/react";
import AppRouter from "./AppRouter";
import { useIdleTimer } from 'react-idle-timer'
import { BroadcastChannel } from "broadcast-channel";
import "./i18n";
import i18next from "i18next";


const RouterLoader = () => {
  const { getRemainingTime, getLastActiveTime } = useIdleTimer({
    timeout: 1000 * 60 * 60 * 72, // 72hrs
    onIdle: handleOnIdle,
    crossTab: true
  })
  return (
    <Provider store={store}>
      <PersistGate loading={null} persistor={persistor}>
        <Router>
          <Switch>
            <AppRouter />
          </Switch>
        </Router>
      </PersistGate>
    </Provider>
  )
}
const handleOnIdle = event => {
  localStorage.clear(); //clear persisting user data from local storage
  localStorage.setItem("selectedLanguage", i18next.language);
  const logoutChannel = new BroadcastChannel("logout");
  logoutChannel.postMessage("LOGOUT_SUCCESS");
}

export default RouterLoader;
if (document.getElementById("router")) {
  ReactDOM.render(<RouterLoader />, document.getElementById("router"));
}

