import Api from "../../../common/Api";
import {
  INSPECTION_LIBRARY_LOADING,
  GET_INSPECTION_LIBRARY_SUCCESS,
  GET_INSPECTION_LIBRARY_FAILED,
  ADD_INSPECTION_SUCCESS,
  ADD_INSPECTION_FAILED,
  SET_INSPECTION_MESSAGE,
  DELETE_INSPECTION_LIBRARY_SUCCESS,
  DELETE_INSPECTION_LIBRARY_FAILED,
  UPDATE_INSPECTION_SUCCESS,
  UPDATE_INSPECTION_FAILED,
  RESET_INSPECTION_MESSAGE,
  RESET_INSPECTION_SUCCESS,
} from "../constants/actiontypes";

export const getInspectionLibrary = (token, pagination, search="") => {
  return async (dispatch) => {
    try {
      dispatch(setInspectionLoading());
      const response = await Api.getInspectionLibrary(token, pagination, search);
      if (response && response.data.success === "ok") {
        dispatch(getInspectionLibrarySuccess(response.data));
      } else {
        dispatch(getInspectionLibraryFailed());
      }
    } catch (e) {
      dispatch(getInspectionLibraryFailed());
      console.log("get inspection library failed", e);
    }
  };
};

export function setInspectionLoading() {
  return {
    type: INSPECTION_LIBRARY_LOADING,
  };
}

export function getInspectionLibrarySuccess(data) {
  return {
    type: GET_INSPECTION_LIBRARY_SUCCESS,
    payload: data,
  };
}

export function getInspectionLibraryFailed() {
  return {
    type: GET_INSPECTION_LIBRARY_FAILED,
  };
}

export function setMessage(data) {
  return {
    type: SET_INSPECTION_MESSAGE,
    payload: data,
  };
}

export const addInspection = (params, token) => {
  return async (dispatch) => {
    try {
      dispatch(setInspectionLoading());
      const response = await Api.addInspection(params, token);
      if (response && response.data.success === "ok") {
        dispatch(addInspectionSuccess(response.data.result));
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "success", content: response.data.message })
          );
      } else {
        dispatch(addInspectionFailed());
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "error", content: response.data.message })
          );
      }
    } catch (e) {
      dispatch(addInspectionFailed());
      console.log("add inspection failed", e);
    }
  };
};

export function addInspectionSuccess(data) {
  return {
    type: ADD_INSPECTION_SUCCESS,
    payload: data,
  };
}

export function addInspectionFailed() {
  return {
    type: ADD_INSPECTION_FAILED,
  };
}

export const deleteInspectionLib = (InspectionId, token) => {
  return async (dispatch) => {
    try {
      const response = await Api.deleteInspectionLib(InspectionId, token);
      if (response && response.success === "ok") {
        dispatch(deleteInspectionLibSuccess(response));
        response &&
          dispatch(setMessage({ mode: "success", content: response.message }));
      } else {
        dispatch(deleteInspectionLibFailed());
        response &&
          dispatch(setMessage({ mode: "error", content: response.message }));
      }
    } catch (e) {
      dispatch(deleteInspectionLibFailed());
      console.log(e);
    }
  };
};

export function deleteInspectionLibSuccess(data) {
  return {
    type: DELETE_INSPECTION_LIBRARY_SUCCESS,
  };
}

export const updateInspection = (params, token) => {
  return async (dispatch) => {
    try {
      dispatch(setInspectionLoading());
      const response = await Api.updateInspection(params, token);
      if (response && response.data.success === "ok") {
        dispatch(updateInspectionSuccess(response.data.result));
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "success", content: response.data.message })
          );
      } else {
        dispatch(updateInspectionFailed());
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "error", content: response.data.message })
          );
      }
    } catch (e) {
      dispatch(updateInspectionFailed());
      console.log("updateInspectionFailed", e);
    }
  };
};

export function deleteInspectionLibFailed() {
  return {
    type: DELETE_INSPECTION_LIBRARY_FAILED,
  };
}

export function updateInspectionSuccess(data) {
  return {
    type: UPDATE_INSPECTION_SUCCESS,
    payload: data,
  };
}

export function updateInspectionFailed() {
  return {
    type: UPDATE_INSPECTION_FAILED,
  };
}

export function resetInspectionMessage() {
  return {
    type: RESET_INSPECTION_MESSAGE,
  };
}

export function resetInspectionSuccess() {
  return {
    type: RESET_INSPECTION_SUCCESS,
  };
}
