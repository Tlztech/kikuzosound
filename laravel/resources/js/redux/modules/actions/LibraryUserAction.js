import Api from "../../../common/Api";
import {
  LIB_USER_LOADING,
  GET_LIB_USER_SUCCESS,
  GET_LIB_USER_FAILED,
} from "../constants/actiontypes";

export const getLibraryUser = (token) => {
  return async (dispatch) => {
    try {
      dispatch(setLibraryUserLoading());
      const response = await Api.getLibraryUser(token);
      if (response && response.data.success === "ok") {
        dispatch(getLibraryUserSuccess(response.data.result));
      } else {
        dispatch(getLibraryUserFailed());
      }
    } catch (e) {
      dispatch(getLibraryUserFailed());
      console.log("getLibraryUser", e);
    }
  };
};

export function setLibraryUserLoading() {
  return {
    type: LIB_USER_LOADING,
  };
}

export function getLibraryUserSuccess(data) {

  return {
    type: GET_LIB_USER_SUCCESS,
    payload: data,
  };
}

export function getLibraryUserFailed() {

  return {
    type: GET_LIB_USER_FAILED,
  };
}
