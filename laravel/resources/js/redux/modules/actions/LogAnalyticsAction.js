import Api from "../../../common/Api";
import {
  GET_LOGANALYTICSRESULT_SUCCESS,
  GET_LOGANALYTICSRESULT_FAILED,
  LOGANALYTICSRESULT_LOADING,
} from "../constants/actiontypes";

export const getLogAnalytics = (examId, examGroupdId, scope, abilty, token) => {
  return async (dispatch) => {
    try {
      dispatch(setLogAnalyticsLoading());
      const response = await Api.getExamResult(
        examId,
        examGroupdId,
        scope,
        abilty,
        token
      );
      if (response && response.data.success === "ok") {
        dispatch(getLogAnalyticsSuccess(response.data.result));
      } else {
        dispatch(getLogAnalyticsFailed());
      }
    } catch (e) {
      dispatch(getLogAnalyticsFailed());
      console.log("getLogAnalytics", e);
    }
  };
};

export function setLogAnalyticsLoading() {
  return {
    type: LOGANALYTICSRESULT_LOADING,
  };
}

export function getLogAnalyticsSuccess(data) {
  return {
    type: GET_LOGANALYTICSRESULT_SUCCESS,
    payload: data,
  };
}

export function getLogAnalyticsFailed() {
  return {
    type: GET_LOGANALYTICSRESULT_FAILED,
  };
}
