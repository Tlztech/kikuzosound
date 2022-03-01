import Api from "../../../common/Api";
import {
  QUIZ_LOADING,
  GET_QUIZ_SUCCESS,
  GET_QUIZ_FAILED,
  GET_ALL_QUIZZES_SUCCESS,
  GET_ALL_QUIZZES_FAILED,
  ADD_QUIZ_SUCCESS,
  ADD_QUIZ_FAILED,
  DELETE_QUIZ_SUCCESS,
  DELETE_QUIZ_FAILED,
  SET_QUIZ_MESSAGE,
  RESET_QUIZ_MESSAGE,
  UPDATE_QUIZ_SUCCESS,
  UPDATE_QUIZ_FAILED,
  GET_SINGLE_QUIZ_SUCCESS,
  GET_SINGLE_QUIZ_FAILED,
} from "../constants/actiontypes";

export const getQuizzes = (token) => {
  return async (dispatch) => {
    try {
      dispatch(setQuizLoading());
      const response = await Api.getQuizzes(token);
      if (response && response.data.success === "ok") {
        dispatch(getQuizSuccess(response.data.result));
      } else {
        dispatch(getQuizFailed());
      }
    } catch (e) {
      dispatch(getQuizFailed());
      console.log("get quiz", e);
    }
  };
};

export function setQuizLoading() {
  return {
    type: QUIZ_LOADING,
  };
}

export function setMessage(data) {
  return {
    type: SET_QUIZ_MESSAGE,
    payload: data,
  };
}

export function getQuizSuccess(data) {
  return {
    type: GET_QUIZ_SUCCESS,
    payload: data,
  };
}

export function getQuizFailed() {
  return {
    type: GET_QUIZ_FAILED,
  };
}

export const getAllQuizzes = (token, pagination, search="") => {
  return async (dispatch) => {
    try {
      dispatch(setQuizLoading());
      const response = await Api.getAllQuizzes(token, pagination, search);
      if (response && response.data.success === "ok") {
        dispatch(getAllQuizzesSuccess(response.data));
      } else {
        dispatch(getAllQuizzesFailed());
      }
    } catch (e) {
      dispatch(getAllQuizzesFailed());
      console.log("get all quizzes failed", e);
    }
  };
};

export function getAllQuizzesSuccess(data) {
  return {
    type: GET_ALL_QUIZZES_SUCCESS,
    payload: data,
  };
}

export function getAllQuizzesFailed() {
  return {
    type: GET_ALL_QUIZZES_FAILED,
  };
}

/////////////////
// add Quizzess
////////////////
export const addQuizzes = (Quizzes, token) => {
  return async (dispatch) => {
    try {
      dispatch(setQuizLoading());
      const response = await Api.addQuizzes(Quizzes, token);
      if (response && response.data.success === "ok") {
        dispatch(addQuizzesSuccess(response.data.result));
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "success", content: response.data.message })
          );
      } else {
        dispatch(addQuizzesFailed());
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "error", content: response.data.message })
          );
      }
    } catch (e) {
      dispatch(addQuizzesFailed());
      console.log("update quiz failed", e);
    }
  };
};

export function addQuizzesSuccess(data) {
  return {
    type: ADD_QUIZ_SUCCESS,
    payload: data,
  };
}

export function addQuizzesFailed() {
  return {
    type: ADD_QUIZ_FAILED,
  };
}

/////////////////
// delete Quizzess
////////////////

export const deleteQuizzes = (QuizzesId, token) => {
  return async (dispatch) => {
    try {
      const response = await Api.deleteQuizzes(QuizzesId, token);
      if (response && response.success === "ok") {
        dispatch(deleteQuizzesSuccess(response));
        response &&
          dispatch(setMessage({ mode: "success", content: response.message }));
      } else {
        dispatch(deleteQuizzesFailed());
        response &&
          dispatch(setMessage({ mode: "error", content: response.message }));
      }
    } catch (e) {
      dispatch(deleteQuizzesFailed());
      console.log(e);
    }
  };
};

export function deleteQuizzesSuccess(data) {
  return {
    type: DELETE_QUIZ_SUCCESS,
    payload: data,
  };
}

export function deleteQuizzesFailed() {
  return {
    type: DELETE_QUIZ_FAILED,
  };
}

export const updateQuizzes = (Quizzes, token) => {
  return async (dispatch) => {
    try {
      dispatch(setQuizLoading());
      const response = await Api.updateQuizzes(Quizzes, token);
      if (response && response.data.success === "ok") {
        dispatch(updateQuizzesSuccess(response.data.result));
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "success", content: response.data.message })
          );
      } else {
        dispatch(updateQuizzesFailed());
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "error", content: response.data.message })
          );
      }
    } catch (e) {
      dispatch(updateQuizzesFailed());
      console.log("updateQuizzesFailed", e);
    }
  };
};

export function updateQuizzesSuccess(data) {
  return {
    type: UPDATE_QUIZ_SUCCESS,
    payload: data,
  };
}

export function updateQuizzesFailed() {
  return {
    type: UPDATE_QUIZ_FAILED,
  };
}

/////////////////
// reset Quizzess
////////////////
export function resetQuizzesMessage() {
  return {
    type: RESET_QUIZ_MESSAGE,
  };
}

export const getSingleQuiz = (token, id) => {
  return async (dispatch) => {
    try {
      dispatch(setQuizLoading());
      const response = await Api.getSingleQuizzes(token, id);
      if (response && response.data.success === "ok") {
        dispatch(getSingleQuizSuccess(response.data.result));
      } else {
        dispatch(getSingleQuizFailed());
      }
    } catch (e) {
      dispatch(getSingleQuizFailed());
      console.log("get single quiz failed", e);
    }
  };
};

export function getSingleQuizSuccess(data) {
  return {
    type: GET_SINGLE_QUIZ_SUCCESS,
    payload: data,
  };
}

export function getSingleQuizFailed() {
  return {
    type: GET_SINGLE_QUIZ_FAILED,
  };
}
