import Api from "../../../common/Api";
import {
  HOME_LOADING,
  GET_HOME_SUCCESS,
  GET_HOME_FAILED,
  SET_HOME_MESSAGE,
  LOGOUT_SUCCESS
} from "../constants/actiontypes";

export function setHomeLoading() {
  return {
    type: HOME_LOADING,
  };
}

/////////////////
// get home data
////////////////
export function getHomeSuccess(data) {
  return {
    type: GET_HOME_SUCCESS,
    payload: data,
  };
}

export function getHomeFailed() {
  return {
    type: GET_HOME_FAILED,
  };
}

export function forceLogOut() {
  return {
    type: LOGOUT_SUCCESS,
  };
}

export const getHomeData = (token, page=0) => {
  return async (dispatch) => {
    try {
      dispatch(setHomeLoading());
      const response = await Api.getHomeData(token,page);
      if (response && response.data.success === "ok") {
        dispatch(getHomeSuccess(response.data));
      } else {
        dispatch(getHomeFailed());
        dispatch(forceLogOut());
      }
    } catch (e) {
      dispatch(getHomeFailed());
      console.log("getHomeFailed", e);
    }
  };
};