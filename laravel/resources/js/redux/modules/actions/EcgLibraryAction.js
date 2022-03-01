import Api from "../../../common/Api";
import {
  ECG_LIBRARY_LOADING,
  ADD_ECG_FAILED,
  ADD_ECG_SUCCESS,
  GET_ECG_LIBRARY_SUCCESS,
  GET_ECG_LIBRARY_FAILED,
  UPDATE_ECG_SUCCESS,
  UPDATE_ECG_FAILED,
  DELETE_ECG_SUCCESS,
  DELETE_ECG_FAILED,
  SET_ECG_MESSAGE,
  RESET_ECG_MESSAGE,
} from "../constants/actiontypes";

export const getEcg = (token, pagination, search="") => {
  return async (dispatch) => {
    try {
      dispatch(setECGLoading());
      const response = await Api.getEcg(token, pagination, search);
      if (response && response.data.success === "ok") {
        dispatch(getECGSuccess(response.data));
      } else {
        dispatch(getECGFailed());
      }
    } catch (e) {
      dispatch(getECGFailed());
      console.log("getECG", e);
    }
  };
};

export function setECGLoading() {
  return {
    type: ECG_LIBRARY_LOADING,
  };
}

export function getECGSuccess(data) {
  return {
    type: GET_ECG_LIBRARY_SUCCESS,
    payload: data,
  };
}

export function getECGFailed() {
  return {
    type: GET_ECG_LIBRARY_FAILED,
  };
}

export const addEcg = (ECG, token) => {
  return async (dispatch) => {
    try {
      dispatch(setECGLoading());
      const response = await Api.addEcg(ECG, token);
      if (response && response.data.success === "ok") {
        dispatch(addECGSuccess(response.data.result));
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "success", content: response.data.message })
          );
      } else {
        dispatch(addECGFailed());
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "error", content: response.data.message })
          );
      }
    } catch (e) {
      dispatch(addECGFailed());
      console.log("addECGs fail", e);
    }
  };
};

export function addECGSuccess(data) {
  return {
    type: ADD_ECG_SUCCESS,
    payload: data,
  };
}

export function addECGFailed() {
  return {
    type: ADD_ECG_FAILED,
  };
}

export const deleteEcg = (ECGId, token) => {
  return async (dispatch) => {
    try {
      const response = await Api.deleteEcg(ECGId, token);
      if (response && response.success === "ok") {
        dispatch(ECGDeleteSuccess(response));
        response &&
          dispatch(setMessage({ mode: "success", content: response.message }));
      } else {
        dispatch(ECGDeleteFailed());
        response &&
          dispatch(setMessage({ mode: "error", content: response.message }));
      }
    } catch (e) {
      dispatch(ECGDeleteFailed());
      console.log(e);
    }
  };
};

export function setMessage(data) {
  console.log("msg", data);
  return {
    type: SET_ECG_MESSAGE,
    payload: data,
  };
}

export function ECGDeleteSuccess(data) {
  return {
    type: DELETE_ECG_SUCCESS,
    payload: data,
  };
}

export function ECGDeleteFailed() {
  return {
    type: DELETE_ECG_FAILED,
  };
}

export const updateEcg = (id, ECG, token) => {
  return async (dispatch) => {
    try {
      dispatch(setECGLoading());
      const response = await Api.updateEcg(id, ECG, token);
      if (response && response.data.success === "ok") {
        dispatch(updateECGSuccess(response.data.result));
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "success", content: response.data.message })
          );
      } else {
        dispatch(updateECGFailed());
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "success", content: response.data.message })
          );
      }
      // response && response.data && dispatch(setMessage(response.data.message));
    } catch (e) {
      dispatch(updateECGFailed());
      console.log("updateECGs fail", e);
    }
  };
};

export function updateECGSuccess(data) {
  return {
    type: UPDATE_ECG_SUCCESS,
    payload: data,
  };
}

export function updateECGFailed() {
  return {
    type: UPDATE_ECG_FAILED,
  };
}

export function resetEcgMessage() {
  return {
    type: RESET_ECG_MESSAGE,
  };
}
