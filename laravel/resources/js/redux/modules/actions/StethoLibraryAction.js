import Api from "../../../common/Api";
import {
  STETHO_LIBRARY_LOADING,
  ADD_STETHO_FAILED,
  ADD_STETHO_SUCCESS,
  GET_STETHO_LIBRARY_SUCCESS,
  GET_STETHO_LIBRARY_FAILED,
  UPDATE_STETHO_SUCCESS,
  UPDATE_STETHO_FAILED,
  DELETE_STETHO_SUCCESS,
  DELETE_STETHO_FAILED,
  SET_STETHO_MESSAGE,
  RESET_STETHO_MESSAGE,
} from "../constants/actiontypes";

export const getStetho = (token, pagination, search="") => {
  return async (dispatch) => {
    try {
      dispatch(setStethoLoading());
      const response = await Api.getStetho(token, pagination, search);
      if (response && response.data.success === "ok") {
        dispatch(getStethoSuccess(response.data));
      } else {
        dispatch(getStethoFailed());
      }
    } catch (e) {
      dispatch(getStethoFailed());
      console.log("getStetho", e);
    }
  };
};

export function setStethoLoading() {
  return {
    type: STETHO_LIBRARY_LOADING,
  };
}

export function getStethoSuccess(data) {
  return {
    type: GET_STETHO_LIBRARY_SUCCESS,
    payload: data,
  };
}

export function getStethoFailed() {
  return {
    type: GET_STETHO_LIBRARY_FAILED,
  };
}

export const addStetho = (STETHO, token) => {
  return async (dispatch) => {
    try {
      dispatch(setStethoLoading());
      const response = await Api.addStetho(STETHO, token);
      if (response && response.data.success === "ok") {
        dispatch(addStethoSuccess(response.data.result));
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "success", content: response.data.message })
          );
      } else {
        dispatch(addStethoFailed());
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "error", content: response.data.message })
          );
      }
    } catch (e) {
      dispatch(addStethoFailed());
      console.log("addSTETHOs fail", e);
    }
  };
};

export function addStethoSuccess(data) {
  return {
    type: ADD_STETHO_SUCCESS,
    payload: data,
  };
}

export function addStethoFailed() {
  return {
    type: ADD_STETHO_FAILED,
  };
}

export const deleteStetho = (STETHOId, token) => {
  return async (dispatch) => {
    try {
      const response = await Api.deleteStetho(STETHOId, token);
      if (response && response.success === "ok") {
        dispatch(stethoDeleteSuccess(response));
        response &&
          dispatch(setMessage({ mode: "success", content: response.message }));
      } else {
        dispatch(stethoDeleteFailed());
        response &&
          dispatch(setMessage({ mode: "error", content: response.message }));
      }
    } catch (e) {
      dispatch(stethoDeleteFailed());
      console.log(e);
    }
  };
};

export function setMessage(data) {
  return {
    type: SET_STETHO_MESSAGE,
    payload: data,
  };
}

export function stethoDeleteSuccess(data) {
  return {
    type: DELETE_STETHO_SUCCESS,
    payload: data,
  };
}

export function stethoDeleteFailed() {
  return {
    type: DELETE_STETHO_FAILED,
  };
}

export const updateStetho = (Stetho, token, id) => {
  return async (dispatch) => {
    try {
      dispatch(setStethoLoading());
      const response = await Api.updateStetho(Stetho, token, id);
      if (response && response.data.success === "ok") {
        dispatch(updateStethoSuccess(response.data.result));
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "success", content: response.data.message })
          );
      } else {
        dispatch(updateStethoFailed());
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "error", content: response.data.message })
          );
      }
    } catch (e) {
      dispatch(updateStethoFailed());
      console.log("updateSTETHOs fail", e);
    }
  };
};

export function updateStethoSuccess(data) {
  return {
    type: UPDATE_STETHO_SUCCESS,
    payload: data,
  };
}

export function updateStethoFailed() {
  return {
    type: UPDATE_STETHO_FAILED,
  };
}

export function resetStethoMessage() {
  return {
    type: RESET_STETHO_MESSAGE,
  };
}
