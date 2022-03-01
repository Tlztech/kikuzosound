import Api from "../../../common/Api";
import {
  AUSCULAIDE_LIBRARY_LOADING,
  ADD_AUSCULAIDE_FAILED,
  ADD_AUSCULAIDE_SUCCESS,
  GET_AUSCULAIDE_LIBRARY_SUCCESS,
  GET_AUSCULAIDE_LIBRARY_FAILED,
  UPDATE_AUSCULAIDE_SUCCESS,
  UPDATE_AUSCULAIDE_FAILED,
  DELETE_AUSCULAIDE_SUCCESS,
  DELETE_AUSCULAIDE_FAILED,
  SET_AUSCULIADE_MESSAGE,
  RESET_AUSCULAIDE_MESSAGE,
} from "../constants/actiontypes";

export const getAusculaide = (token, pagination, search="") => {
  return async (dispatch) => {
    try {
      dispatch(setAusculaideLoading());
      const response = await Api.getAusculaides(token, pagination, search);
      if (response && response.data.success === "ok") {
        dispatch(getAusculaideSuccess(response.data));
      } else {
        dispatch(getAusculaideFailed());
      }
    } catch (e) {
      dispatch(getAusculaideFailed());
      console.log("getAusculaide", e);
    }
  };
};

export function setAusculaideLoading() {
  return {
    type: AUSCULAIDE_LIBRARY_LOADING,
  };
}

export function getAusculaideSuccess(data) {
  return {
    type: GET_AUSCULAIDE_LIBRARY_SUCCESS,
    payload: data,
  };
}

export function getAusculaideFailed() {
  return {
    type: GET_AUSCULAIDE_LIBRARY_FAILED,
  };
}

export const addAusculaide = (ausculaide, token) => {
  return async (dispatch) => {
    try {
      dispatch(setAusculaideLoading());
      const response = await Api.addAusculaides(ausculaide, token);
      if (response && response.data.success === "ok") {
        dispatch(addAusculaideSuccess(response.data.result));
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "success", content: response.data.message })
          );
      } else {
        dispatch(addAusculaideFailed());
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "error", content: response.data.message })
          );
      }
    } catch (e) {
      dispatch(addAusculaideFailed());
      console.log("addAusculaides fail", e);
    }
  };
};

export function addAusculaideSuccess(data) {
  return {
    type: ADD_AUSCULAIDE_SUCCESS,
    payload: data,
  };
}

export function addAusculaideFailed() {
  return {
    type: ADD_AUSCULAIDE_FAILED,
  };
}

export const deleteAusculaide = (ausculaideId, token) => {
  return async (dispatch) => {
    try {
      const response = await Api.deleteAusculaide(ausculaideId, token);
      if (response && response.success === "ok") {
        dispatch(ausculaideDeleteSuccess(response));
        response &&
          dispatch(setMessage({ mode: "success", content: response.message }));
      } else {
        dispatch(ausculaideDeleteFailed());
        response &&
          dispatch(setMessage({ mode: "error", content: response.message }));
      }
    } catch (e) {
      dispatch(ausculaideDeleteFailed());
      console.log(e);
    }
  };
};

export function setMessage(data) {
  return {
    type: SET_AUSCULIADE_MESSAGE,
    payload: data,
  };
}

export function ausculaideDeleteSuccess(data) {
  return {
    type: DELETE_AUSCULAIDE_SUCCESS,
    payload: data,
  };
}

export function ausculaideDeleteFailed() {
  return {
    type: DELETE_AUSCULAIDE_FAILED,
  };
}

export const updateAusculaide = (Ausculaide, token, id) => {
  return async (dispatch) => {
    try {
      dispatch(setAusculaideLoading());
      const response = await Api.updateAusculaide(Ausculaide, token, id);
      if (response && response.data.success === "ok") {
        dispatch(updateAusculaideSuccess(response.data.result));
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "success", content: response.data.message })
          );
      } else {
        dispatch(updateAusculaideFailed());
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "error", content: response.data.message })
          );
      }
      // response && response.data && dispatch(setMessage(response.data.message));
    } catch (e) {
      dispatch(updateAusculaideFailed());
      console.log("updateAusculaides fail", e);
    }
  };
};

export const updateAusculaideUrl = (url, token, id) => {
  return async (dispatch) => {
    try {
      dispatch(setAusculaideLoading());
      const response = await Api.updateAusculaideUrl(url, token, id);
      if (response && response.data.success === "ok") {
        dispatch(updateAusculaideSuccess(response.data.result));
      } else {
        dispatch(updateAusculaideFailed());
      }
    } catch (e) {
      dispatch(updateAusculaideFailed());
      console.log("updateAusculaides fail", e);
    }
  };
};

export function updateAusculaideSuccess(data) {
  return {
    type: UPDATE_AUSCULAIDE_SUCCESS,
    payload: data,
  };
}

export function updateAusculaideFailed() {
  return {
    type: UPDATE_AUSCULAIDE_FAILED,
  };
}

export function resetAusculaideMessage() {
  return {
    type: RESET_AUSCULAIDE_MESSAGE,
  };
}
