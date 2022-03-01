import {
  GET_LOGANALYTICSRESULT_SUCCESS,
  GET_LOGANALYTICSRESULT_FAILED,
  LOGANALYTICSRESULT_LOADING,
} from "../constants/actiontypes";

const initialState = {
  isLoading: true,
  logAnalyticList: null,
};

export default (state = initialState, action) => {
  switch (action.type) {
    case LOGANALYTICSRESULT_LOADING:
      return {
        ...state,
        isLoading: true,
      };
    case GET_LOGANALYTICSRESULT_SUCCESS:
      return {
        ...state,
        logAnalyticList: action.payload,
        isLoading: false,
      };
    case GET_LOGANALYTICSRESULT_FAILED:
      return {
        ...state,
        logAnalyticList: null,
        isLoading: false,
      };

    default:
      return state;
  }
};
