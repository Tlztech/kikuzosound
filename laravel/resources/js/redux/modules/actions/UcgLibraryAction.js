import Api from "../../../common/Api";
import {
  UCG_LIBRARY_LOADING,
  GET_UCG_LIBRARY_SUCCESS,
  GET_UCG_LIBRARY_FAILED,
  ADD_UCG_LIBRARY_SUCCESS,
  ADD_UCG_LIBRARY_FAILED,
  UPDATE_UCG_SUCCESS,
  UPDATE_UCG_FAILED,
  DELETE_UCG_SUCCESS,
  DELETE_UCG_FAILED,
  SET_UCG_MESSAGE,
  RESET_UCG_MESSAGE,
} from "../constants/actiontypes";

export const getUcgLibrary = (token, pagination = "all", search="") => {
  return async (dispatch) => {
    try {
      dispatch(setUcgLoading());
      const response = await Api.getUcgLibrary(token, pagination, search);
      if (response && response.data.success === "ok") {
        dispatch(getUcgLibrarySuccess(response.data));
      } else {
        dispatch(getUcgLibraryFailed());
      }
    } catch (e) {
      dispatch(getUcgLibraryFailed());
      console.log("get ucg failed", e);
    }
  };
};

export function setUcgLoading() {
  return {
    type: UCG_LIBRARY_LOADING,
  };
}

export function getUcgLibrarySuccess(data) {
  return {
    type: GET_UCG_LIBRARY_SUCCESS,
    payload: data,
  };
}

export function getUcgLibraryFailed() {
  return {
    type: GET_UCG_LIBRARY_FAILED,
  };
}

export const addUcgLibrary = (token, params) => {
  return async (dispatch) => {
    try {
      dispatch(setUcgLoading());
      const response = await Api.addUcgLibrary(token, params);
      if (response && response.data.success === "ok") {
        dispatch(addUcgLibrarySuccess(response.data.result));
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "success", content: response.data.message })
          );
      } else {
        dispatch(addUcgLibraryFailed());
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "error", content: response.data.message })
          );
      }
    } catch (e) {
      dispatch(addUcgLibraryFailed());
      console.log("add ucg failed", e);
    }
  };
};

export function addUcgLibrarySuccess(data) {
  return {
    type: ADD_UCG_LIBRARY_SUCCESS,
    payload: data,
  };
}

export function addUcgLibraryFailed() {
  return {
    type: ADD_UCG_LIBRARY_FAILED,
  };
}

export const deleteUcg = (UcgId, token) => {
  return async (dispatch) => {
    try {
      const response = await Api.deleteUcg(UcgId, token);
      if (response && response.success === "ok") {
        dispatch(ucgDeleteSuccess(response));
        response &&
          dispatch(setMessage({ mode: "success", content: response.message }));
      } else {
        dispatch(ucgDeleteFailed());
        response &&
          dispatch(setMessage({ mode: "error", content: response.message }));
      }
    } catch (e) {
      dispatch(ucgDeleteFailed());
      console.log(e);
    }
  };
};

export function setMessage(data) {
  return {
    type: SET_UCG_MESSAGE,
    payload: data,
  };
}

export function ucgDeleteSuccess(data) {
  return {
    type: DELETE_UCG_SUCCESS,
    payload: data,
  };
}

export function ucgDeleteFailed() {
  return {
    type: DELETE_UCG_FAILED,
  };
}

export const updateUcg = (id, UCG, token) => {
  return async (dispatch) => {
    try {
      dispatch(setUcgLoading());
      const response = await Api.updateUcg(id, UCG, token);
      if (response && response.data.success === "ok") {
        dispatch(updateUcgSuccess(response.data.result));
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "success", content: response.data.message })
          );
      } else {
        dispatch(updateUcgFailed());
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "success", content: response.data.message })
          );
      }
    } catch (e) {
      dispatch(updateUcgFailed());
      console.log("updateUCGs fail", e);
    }
  };
};

export function updateUcgSuccess(data) {
  return {
    type: UPDATE_UCG_SUCCESS,
    payload: data,
  };
}

export function updateUcgFailed() {
  return {
    type: UPDATE_UCG_FAILED,
  };
}

export function resetUcgMessage() {
  return {
    type: RESET_UCG_MESSAGE,
  };
}
