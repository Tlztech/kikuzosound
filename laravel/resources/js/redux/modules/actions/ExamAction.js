import Api from "../../../common/Api";
import {
  EXAM_LOADING,
  GET_EXAMS_SUCCESS,
  GET_EXAMS_FAILED,
  ADD_EXAMS_FAILED,
  ADD_EXAMS_SUCCESS,
  SET_MESSAGE,
  DELETE_EXAMS_FAILED,
  DELETE_EXAMS_SUCCESS,
  UPDATE_EXAMS_SUCCESS,
  UPDATE_EXAMS_FAILED,
  RESET_EXAM_MESSAGE,
} from "../constants/actiontypes";

export const getExams = (token, pagination,search="") => {
  return async (dispatch) => {
    try {
      dispatch(setExamLoading());
      const response = await Api.getExams(token, pagination, search);
      if (response && response.data.success === "ok") {
        dispatch(getExamSuccess(response.data));
      } else {
        dispatch(getExamFailed());
      }
    } catch (e) {
      dispatch(getExamFailed());
      console.log("getExams", e);
    }
  };
};

export function setExamLoading() {
  return {
    type: EXAM_LOADING,
  };
}

export function getExamSuccess(data) {
  return {
    type: GET_EXAMS_SUCCESS,
    payload: data,
  };
}

export function getExamFailed() {
  return {
    type: GET_EXAMS_FAILED,
  };
}

export const addExam = (exam, quiz, token) => {
  return async (dispatch) => {
    try {
      dispatch(setExamLoading());
      const response = await Api.addExams(exam, quiz, token);
      if (response && response.data.success === "ok") {
        dispatch(addExamSuccess(response.data.result));
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "success", content: response.data.message })
          );
      } else {
        dispatch(addExamFailed());
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "error", content: response.data.message })
          );
      }
    } catch (e) {
      dispatch(addExamFailed());
      console.log("addExams fail", e);
    }
  };
};

export function addExamSuccess(data) {
  return {
    type: ADD_EXAMS_SUCCESS,
    payload: data,
  };
}

export function setMessage(data) {
  return {
    type: SET_MESSAGE,
    payload: data,
  };
}

export function addExamFailed() {
  return {
    type: ADD_EXAMS_FAILED,
  };
}

export const deleteExam = (examId, token) => {
  return async (dispatch) => {
    try {
      const response = await Api.deleteExam(examId, token);
      if (response && response.success === "ok") {
        dispatch(examDeleteSuccess(response));
        response &&
          dispatch(setMessage({ mode: "success", content: response.message }));
      } else {
        dispatch(examDeleteFailed());
        response &&
          dispatch(setMessage({ mode: "error", content: response.message }));
      }
    } catch (e) {
      dispatch(examDeleteFailed());
      console.log(e);
    }
  };
};

export function examDeleteSuccess(data) {
  return {
    type: DELETE_EXAMS_SUCCESS,
    payload: data,
  };
}

export function examDeleteFailed() {
  return {
    type: DELETE_EXAMS_FAILED,
  };
}

export const updateExam = (exam, quiz, token, userId, newExamResult) => {
  return async (dispatch) => {
    try {
      dispatch(setExamLoading());
      const response = await Api.updateExams(
        exam,
        quiz,
        token,
        userId,
        newExamResult
      );
      if (response && response.data.success === "ok") {
        dispatch(updateExamSuccess(response.data.result));
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "success", content: response.data.message })
          );
      } else {
        dispatch(updateExamFailed());
        response &&
          response.data &&
          dispatch(
            setMessage({ mode: "success", content: response.data.message })
          );
      }
    } catch (e) {
      dispatch(updateExamFailed());
      console.log("updateExams fail", e);
    }
  };
};

export function updateExamSuccess(data) {
  return {
    type: UPDATE_EXAMS_SUCCESS,
    payload: data,
  };
}

export function updateExamFailed() {
  return {
    type: UPDATE_EXAMS_FAILED,
  };
}

export function resetExamMessage() {
  return {
    type: RESET_EXAM_MESSAGE,
  };
}
