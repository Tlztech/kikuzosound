import Api from "../../../common/Api";
import {
  CHANGE_PASSWORD_LOADING,
  CHANGE_PASSWORD_SUCCESS,
  CHANGE_PASSWORD_FAILED,
  SET_CHANGE_PASSWORD_MESSAGE,
} from "../constants/actiontypes";

export const changePassword = (userInfo, token) => {
  return async (dispatch) => {
    try {
      dispatch(setChangePasswordLoading());
      const response = await Api.changePassword(userInfo, token);
      if (response && response.data && response.data.success === "ok") {
        dispatch(getchangePasswordSuccess());
        dispatch(setMessage({ mode: "success", content: "change_password_success" }));
      } else {
        dispatch(getchangePasswordFailed(response.data.message));
      }
    } catch (e) {
      dispatch(getchangePasswordFailed());
      console.log("change password failed", e);
    }
  };
};

export function setChangePasswordLoading() {
  return {
    type: CHANGE_PASSWORD_LOADING,
  };
}

export function getchangePasswordSuccess() {
  return {
    type: CHANGE_PASSWORD_SUCCESS,
  };
}

export function getchangePasswordFailed(msg) {
  return {
    type: CHANGE_PASSWORD_FAILED,
    payload: msg,
  };
}

export function setMessage(data) {
  return {
    type: SET_CHANGE_PASSWORD_MESSAGE,
    payload: data,
  };
}
