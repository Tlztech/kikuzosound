import i18next from "i18next";
import { BroadcastChannel } from "broadcast-channel";

import Api from "../../../common/Api";
import {
  LOGIN_LOADING,
  LOGIN_SUCCESS,
  LOGIN_FAILED,
  LOGOUT_SUCCESS,
  SET_TOAST,
  SET_ERROR,
} from "../constants/actiontypes";

export const loginUser = (email, password) => {
  return async (dispatch) => {
    try {
      dispatch(setLoginLoading());
      const response = await Api.login(email, password);
      if (response && response.data.success === "ok") {
        dispatch(loginSuccess(response.data.result));
      } else {
        dispatch(loginFailed(response.data.message));
      }
    } catch (e) {
      dispatch(loginFailed());
      console.log("login failed", e);
    }
  };
};

export const logout = (token) => {
  return async (dispatch) => {
    try {
      const response = await Api.logout(token);
      if (response && response.data.success === "ok") {
        dispatch(logoutSuccess());
        localStorage.clear(); //clear persisting user data from local storage
        localStorage.setItem("selectedLanguage", i18next.language);
        const logoutChannel = new BroadcastChannel("logout");
        logoutChannel.postMessage("LOGOUT_SUCCESS");
      }
    } catch (e) {
      console.log("logout failed", e);
    }
  };
};

export const setToast = () => {
  return {
    type: SET_TOAST,
  };
};

export const setError = () => {
  return {
    type: SET_ERROR,
  };
};

export const setLoginLoading = () => {
  return {
    type: LOGIN_LOADING,
  };
};

export const loginSuccess = (data) => {
  return {
    type: LOGIN_SUCCESS,
    payload: data,
  };
};

export const loginFailed = (data) => {
  return {
    type: LOGIN_FAILED,
    payload: data,
  };
};

export const logoutSuccess = () => {
  return {
    type: LOGOUT_SUCCESS,
  };
};
