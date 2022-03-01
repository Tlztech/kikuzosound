import Api from "../../../common/Api";
import {
  PALPATION_LIBRARY_LOADING,
  GET_PALPATION_LIBRARY_SUCCESS,
  GET_PALPATION_LIBRARY_FAILED,
  ADD_PALPATION_LIBRARY_SUCCESS,
  ADD_PALPATION_LIBRARY_FAILED,
  SET_PALPATION_MESSAGE,
  RESET_PALPATION_MESSAGE,
  UPDATE_PALPATION_SUCCESS,
  UPDATE_PALPATION_FAILED,
  DELETE_PALPATION_SUCCESS,
  DELETE_PALPATION_FAILED,
} from "../constants/actiontypes";

export function getPalpation(token, pagination, search="") {
  return async (dispatch) => {
    try {
      dispatch(setPalpationLoading());
      const response = await Api.getPalpation(token, pagination, search);
      if (response && response.data.success === "ok") {
        dispatch(getPalpationLibrarySuccess(response.data));
      } else {
        dispatch(getPalpationLibraryFailed());
      }
    } catch (e) {
      dispatch(getPalpationLibraryFailed());
      console.log("get palpation library failed", e);
    }
  };
}

export function setPalpationLoading() {
  return {
    type: PALPATION_LIBRARY_LOADING,
  };
}

export function getPalpationLibrarySuccess(data) {
  return {
    type: GET_PALPATION_LIBRARY_SUCCESS,
    payload: data,
  };
}

export function getPalpationLibraryFailed() {
  return {
    type: GET_PALPATION_LIBRARY_FAILED,
  };
}

export function addPalpation(Palpation, token) {
  return async (dispatch) => {
    try {
      dispatch(setPalpationLoading());
      const response = await Api.addPalpation(Palpation, token);
      if (response && response.data.success === "ok") {
        dispatch(addPalpationSuccess(response.data.result));
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "success", content: response.data.message })
          );
      } else {
        dispatch(addPalpationFailed());
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "error", content: response.data.message })
          );
      }
    } catch (e) {
      dispatch(addPalpationFailed());
      console.log("addPalpations fail", e);
    }
  };
}

export function addPalpationSuccess(data) {
  return {
    type: ADD_PALPATION_LIBRARY_SUCCESS,
    payload: data,
  };
}

export function addPalpationFailed() {
  return {
    type: ADD_PALPATION_LIBRARY_FAILED,
  };
}

export function deletePalpation(PalpationId, token) {
  return async (dispatch) => {
    try {
      const response = await Api.deletePalpation(PalpationId, token);
      if (response && response.success === "ok") {
        dispatch(palpationDeleteSuccess(response));
        response &&
          dispatch(setMessage({ mode: "success", content: response.message }));
      } else {
        dispatch(palpationDeleteFailed());
        response &&
          dispatch(setMessage({ mode: "error", content: response.message }));
      }
    } catch (e) {
      dispatch(palpationDeleteFailed());
      console.log(e);
    }
  };
}

export function setMessage(data) {
  return {
    type: SET_PALPATION_MESSAGE,
    payload: data,
  };
}

export function palpationDeleteSuccess(data) {
  return {
    type: DELETE_PALPATION_SUCCESS,
    payload: data,
  };
}

export function palpationDeleteFailed() {
  return {
    type: DELETE_PALPATION_FAILED,
  };
}

export function updatePalpation(Palpation, token, id, newPalpationResult) {
  return async (dispatch) => {
    try {
      dispatch(setPalpationLoading());
      const response = await Api.updatePalpation(
        Palpation,
        token,
        id,
        newPalpationResult
      );
      if (response && response.data.success === "ok") {
        dispatch(updatePalpationSuccess(response.data.result));
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "success", content: response.data.message })
          );
      } else {
        dispatch(updatePalpationFailed());
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "error", content: response.data.message })
          );
      }
      // response && response.data && dispatch(setMessage(response.data.message));
    } catch (e) {
      dispatch(updatePalpationFailed());
      console.log("updatePalpations fail", e);
    }
  };
}

export function updatePalpationSuccess(data) {
  return {
    type: UPDATE_PALPATION_SUCCESS,
    payload: data,
  };
}

export function updatePalpationFailed() {
  return {
    type: UPDATE_PALPATION_FAILED,
  };
}

export function resetPalpationMessage() {
  return {
    type: RESET_PALPATION_MESSAGE,
  };
}
