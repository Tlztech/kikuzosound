import Api from "../../../common/Api";
import {
  XRAY_LIBRARY_LOADING,
  GET_XRAY_LIBRARY_SUCCESS,
  GET_XRAY_LIBRARY_FAILED,
  ADD_XRAY_SUCCESS,
  ADD_XRAY_FAILED,
  UPDATE_XRAY_SUCCESS,
  UPDATE_XRAY_FAILED,
  DELETE_XRAY_SUCCESS,
  DELETE_XRAY_FAILED,
  SET_XRAY_MESSAGE,
  RESET_XRAY_MESSAGE,
} from "../constants/actiontypes";

export function setXrayLoading() {
  return {
    type: XRAY_LIBRARY_LOADING,
  };
}

export function setMessage(data) {
  return {
    type: SET_XRAY_MESSAGE,
    payload: data,
  };
}

/////////////////
// get xrays
////////////////
export function getXraySuccess(data) {
  return {
    type: GET_XRAY_LIBRARY_SUCCESS,
    payload: data,
  };
}

export function getXrayFailed() {
  return {
    type: GET_XRAY_LIBRARY_FAILED,
  };
}

export const getXrays = (token, pagination, search="") => {
  return async (dispatch) => {
    try {
      dispatch(setXrayLoading());
      const response = await Api.getXrays(token, pagination, search);
      if (response && response.data.success === "ok") {
        dispatch(getXraySuccess(response.data));
      } else {
        dispatch(getXrayFailed());
      }
    } catch (e) {
      dispatch(getXrayFailed());
      console.log("getXrayFailed", e);
    }
  };
};

/////////////////
// add xrays
////////////////
export function addXraySuccess(data) {
  return {
    type: ADD_XRAY_SUCCESS,
    payload: data,
  };
}

export function addXrayFailed() {
  return {
    type: ADD_XRAY_FAILED,
  };
}

export const addXray = (xray, token) => {
  return async (dispatch) => {
    try {
      dispatch(setXrayLoading());
      const response = await Api.addXray(xray, token);
      if (response && response.data.success === "ok") {
        dispatch(addXraySuccess(response.data.result));
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "success", content: response.data.message })
          );
      } else {
        dispatch(addXrayFailed());
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "error", content: response.data.message })
          );
      }
    } catch (e) {
      dispatch(addXrayFailed());
      console.log("addXrayFailed", e);
    }
  };
};

/////////////////
// update xrays
////////////////
export function updateXraySuccess(data) {
  return {
    type: UPDATE_XRAY_SUCCESS,
    payload: data,
  };
}

export function updateXrayFailed() {
  return {
    type: UPDATE_XRAY_FAILED,
  };
}

export const updateXray = (xray, token, id) => {
  return async (dispatch) => {
    try {
      dispatch(setXrayLoading());
      const response = await Api.updateXray(xray, token, id);
      if (response && response.data.success === "ok") {
        dispatch(updateXraySuccess(response.data.result));
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "success", content: response.data.message })
          );
      } else {
        dispatch(updateXrayFailed());
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "success", content: response.data.message })
          );
      }
    } catch (e) {
      dispatch(updateXrayFailed());
      console.log("updateXray fail", e);
    }
  };
};

/////////////////
// delete xrays
////////////////
export function deleteXraySuccess(data) {
  return {
    type: DELETE_XRAY_SUCCESS,
    payload: data,
  };
}

export function deleteXrayFailed() {
  return {
    type: DELETE_XRAY_FAILED,
  };
}

export const deleteXray = (xrayId, token) => {
  return async (dispatch) => {
    try {
      const response = await Api.deleteXray(xrayId, token);
      if (response && response.success === "ok") {
        dispatch(deleteXraySuccess(response));
        response &&
          dispatch(setMessage({ mode: "success", content: response.message }));
      } else {
        dispatch(deleteXrayFailed());
        response &&
          dispatch(setMessage({ mode: "error", content: response.message }));
      }
    } catch (e) {
      dispatch(deleteXrayFailed());
      console.log(e);
    }
  };
};

/////////////////
// reset xrays
////////////////
export function resetXrayMessage() {
  return {
    type: RESET_XRAY_MESSAGE,
  };
}
