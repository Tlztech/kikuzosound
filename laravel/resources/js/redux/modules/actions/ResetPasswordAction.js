import Api from "../../../common/Api";
import {
  GET_RESETPASSWORD_SUCCESS,
  GET_RESETPASSWORD_FAILED,
  SET_NEW_PASSWORD_SUCCESS,
  SET_NEW_PASSWORD_FAILED,
} from "../constants/actiontypes";

export const setNewPassword = (data) => {
  return async (dispatch) => {
    try {
      const response = await Api.setNewPassword(data);
      console.log(response);
      if (response && response.data.success === "ok") {
        dispatch(setNewPasswordSuccess(response.data.message));
      } else {
        dispatch(setNewPasswordFailed(response.data.message));
      }
    } catch (e) {
      dispatch(setNewPasswordFailed());
      console.log("set new password failed", e);
    }
  };
}

export function setNewPasswordSuccess(msg) {
  return {
    type: SET_NEW_PASSWORD_SUCCESS,
    payload: msg,
  };
}

export function setNewPasswordFailed(msg) {
  return {
    type: SET_NEW_PASSWORD_FAILED,
    payload: msg,
  };
}
export const resetPassword = (email) => {
  return async (dispatch) => {
    try {
      const response = await Api.resetPassword(email);
      console.log(response);
      if (response && response.data.success === "ok") {
        dispatch(getResetPasswordSuccess(response.data.message));
      } else {
        dispatch(getResetPasswordFailed(response.data.message));
      }
    } catch (e) {
      dispatch(getResetPasswordFailed());
      console.log("reset password failed", e);
    }
  };
};

export function getResetPasswordSuccess(msg) {
  return {
    type: GET_RESETPASSWORD_SUCCESS,
    payload: msg,
  };
}

export function getResetPasswordFailed(msg) {
  return {
    type: GET_RESETPASSWORD_FAILED,
    payload: msg,
  };
}
