import Api from "../../../common/Api";
import {
  GET_EXAMGROUP_SUCCESS,
  GET_EXAMGROUP_FAILED,
  EXAMGROUP_LOADING,
} from "../constants/actiontypes";

export const getExamGroup = (token) => {
  return async (dispatch) => {
    try {
      dispatch(setExamGroupLoading());
      const response = await Api.getExamGroups(token);
      if (response && response.data.success === "ok") {
        dispatch(getExamGroupSuccess(response.data.result));
      } else {
        dispatch(getExamGroupFailed());
      }
    } catch (e) {
      dispatch(getExamGroupFailed());
      console.log("getExams", e);
    }
  };
};

export function setExamGroupLoading() {
  return {
    type: EXAMGROUP_LOADING,
  };
}

export function getExamGroupSuccess(data) {
  return {
    type: GET_EXAMGROUP_SUCCESS,
    payload: data,
  };
}

export function getExamGroupFailed() {
  return {
    type: GET_EXAMGROUP_FAILED,
  };
}
