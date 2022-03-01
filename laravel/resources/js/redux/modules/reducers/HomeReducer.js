import {
  HOME_LOADING,
  GET_HOME_SUCCESS,
  GET_HOME_FAILED,
} from "../constants/actiontypes";

const initialState = {
  isLoading: true,
  HomeDataList: null,
  home_message: null,
  totalPage: 0,
};

export default (state = initialState, action) => {
  switch (action.type) {
    case HOME_LOADING:
      return {
        ...state,
        isLoading: true,
      };
    case GET_HOME_SUCCESS:
      return {
        ...state,
        HomeDataList: action.payload.result,
        totalPage: action.payload.total_page,
        isLoading: false,
      };
    case GET_HOME_FAILED:
      return {
        ...state,
        HomeDataList: null,
        isLoading: false,
        totalPage: null,
      };
    default:
      return state;
  }
};
