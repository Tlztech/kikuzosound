import React from "react";
import { Redirect } from "react-router-dom";

// redux
import { connect } from "react-redux";

//===================================================
// Component
//===================================================
// tmp
const Auth = (props) =>
  sessionStorage.getItem('CSRF_TOKEN') ? props.children : <Redirect to={"/login"} />;


//===================================================
// Export
//===================================================
export default Auth;
