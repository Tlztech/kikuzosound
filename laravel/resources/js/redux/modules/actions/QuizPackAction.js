import Api from "../../../common/Api";
import {
  QUIZPACK_LOADING,
  GET_QUIZPACK_SUCCESS,
  GET_QUIZPACK_FAILED,
  QUIZPACKINDEX_LOADING,
  GET_QUIZPACKINDEX_SUCCESS,
  GET_QUIZPACKINDEX_FAILED,
  ADD_QUIZPACK_SUCCESS,
  ADD_QUIZPACK_FAILED,
  UPDATE_QUIZPACK_SUCCESS,
  UPDATE_QUIZPACK_FAILED,
  DELETE_QUIZPACK_SUCCESS,
  DELETE_QUIZPACK_FAILED,
  SET_QUIZPACK_MESSAGE,
  RESET_QUIZPACK_MESSAGE,
  GET_SINGLE_QUIZPACK_SUCCESS,
  GET_SINGLE_QUIZPACK_FAILED,
} from "../constants/actiontypes";

export const getQuizPack = (token, pagination, search="") => {
  return async (dispatch) => {
    try {
      dispatch(setQuizPackLoading());
      const response = await Api.getQuizPacks(token, pagination, search);
      if (response && response.data.success === "ok") {
        console.log("=============");
        console.log(response.data);
        dispatch(getQuizPackSuccess(response.data));
      } else {
        dispatch(getQuizPackFailed());
      }
    } catch (e) {
      dispatch(getQuizPackFailed());
      console.log("getQuizPack", e);
    }
  };
};

export function setQuizPackLoading() {
  return {
    type: QUIZPACK_LOADING,
  };
}

export function getQuizPackSuccess(data) {
  return {
    type: GET_QUIZPACK_SUCCESS,
    payload: data,
  };
}

export function getQuizPackFailed() {
  return {
    type: GET_QUIZPACK_FAILED,
  };
}

export const getQuizPackIndex = (token) => {
  return async (dispatch) => {
    try {
      dispatch(setQuizPackIndexLoading());
      const response = await Api.getQuizPacksIndex(token);
      if (response && response.data.success === "ok") {
        dispatch(getQuizPackIndexSuccess(response.data.result));
      } else {
        dispatch(addQuizPackIndexFailed());
      }
    } catch (e) {
      dispatch(addQuizPackIndexFailed());
      console.log("getQuizPacksIndex", e);
    }
  };
};
export function setQuizPackIndexLoading() {
  return {
    type: QUIZPACKINDEX_LOADING,
  };
}

export function getQuizPackIndexSuccess(data) {
  return {
    type: GET_QUIZPACKINDEX_SUCCESS,
    payload: data,
  };
}
export function addQuizPackIndexFailed() {
  return {
    type: GET_QUIZPACKINDEX_FAILED,
  };
}

export const addQuizPack = (quizpack, token) => {
  return async (dispatch) => {
    try {
      dispatch(setQuizPackLoading());
      const response = await Api.addQuizPack(quizpack, token);
      if (response && response.data.success === "ok") {
        dispatch(addQuizPackSuccess(response.data.result));
        dispatch(
          setMessage({ mode: "success", content: response.data.message })
        );
      } else {
        dispatch(addQuizPackFailed());
        dispatch(setMessage({ mode: "error", content: response.data.message }));
      }
    } catch (e) {
      dispatch(addQuizPackFailed());
      console.log("addQuizPack fail", e);
    }
  };
};

export function addQuizPackSuccess(data) {
  return {
    type: ADD_QUIZPACK_SUCCESS,
    payload: data,
  };
}

export function addQuizPackFailed() {
  return {
    type: ADD_QUIZPACK_FAILED,
  };
}

export const deleteQuizPack = (QuizpackId, token) => {
  return async (dispatch) => {
    try {
      const response = await Api.deleteQuizPack(QuizpackId, token);
      if (response && response.success === "ok") {
        dispatch(QuizpackDeleteSuccess(response));
        dispatch(setMessage({ mode: "success", content: response.message }));
      } else {
        dispatch(QuizpackDeleteFailed());
        dispatch(setMessage({ mode: "error", content: response.message }));
      }
    } catch (e) {
      dispatch(QuizpackDeleteFailed());
      console.log(e);
    }
  };
};

export function setMessage(data) {
  return {
    type: SET_QUIZPACK_MESSAGE,
    payload: data,
  };
}

export function QuizpackDeleteSuccess(data) {
  return {
    type: DELETE_QUIZPACK_SUCCESS,
    payload: data,
  };
}

export function QuizpackDeleteFailed() {
  return {
    type: DELETE_QUIZPACK_FAILED,
  };
}

export const updateQuizPack = (Quizpack, token, id) => {
  return async (dispatch) => {
    try {
      dispatch(setQuizPackLoading());
      const response = await Api.updateQuizPack(Quizpack, token, id);
      if (response && response.data.success === "ok") {
        dispatch(updateQuizpackSuccess(response.data.result));
        dispatch(
          setMessage({ mode: "success", content: response.data.message })
        );
      } else {
        dispatch(updateQuizpackFailed());
        dispatch(setMessage({ mode: "error", content: response.data.message }));
      }
    } catch (e) {
      dispatch(updateQuizpackFailed());
      console.log("updateQuizpacks fail", e);
    }
  };
};

export function updateQuizpackSuccess(data) {
  return {
    type: UPDATE_QUIZPACK_SUCCESS,
    payload: data,
  };
}

export function updateQuizpackFailed() {
  return {
    type: UPDATE_QUIZPACK_FAILED,
  };
}

export function resetQuizPackMessage() {
  return {
    type: RESET_QUIZPACK_MESSAGE,
  };
}

export const getSingleQuizPack = (token, quizPackId, lang) => {
  return async (dispatch) => {
    try {
      dispatch(setQuizPackLoading());
      const response = await Api.getSingleQuizPack(token, quizPackId, lang);
      if (response && response.data.success === "ok") {
        dispatch(getSingleQuizPackSuccess(response.data.result));
      } else {
        dispatch(getSingleQuizPackFailed());
      }
    } catch (e) {
      dispatch(getSingleQuizPackFailed());
      console.log("getSingleQuizPack Failed", e);
    }
  };
};

export function getSingleQuizPackSuccess(data) {
  return {
    type: GET_SINGLE_QUIZPACK_SUCCESS,
    payload: data,
  };
}

export function getSingleQuizPackFailed() {
  return {
    type: GET_SINGLE_QUIZPACK_FAILED,
  };
}
