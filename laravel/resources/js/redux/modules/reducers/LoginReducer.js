import {
  LOGIN_LOADING,
  LOGIN_SUCCESS,
  LOGIN_FAILED,
  LOGOUT_SUCCESS,
  SET_TOAST,
  SET_ERROR,
} from "../constants/actiontypes";

const initialState = {
  isLoading: true,
  userInfo: null,
  toast: false,
  loginFailedMessage: null,
};

export default (state = initialState, action) => {
  switch (action.type) {
    case SET_ERROR:
      return {
        ...state,
        loginFailedMessage: null,
      };
    case LOGIN_LOADING:
      return {
        ...state,
        isLoading: true,
      };
    case LOGIN_SUCCESS:
      return {
        ...state,
        userInfo: action.payload,
        toast: true,
        isLoading: false,
        loginFailedMessage: null,
      };
    case LOGIN_FAILED:
      return {
        ...state,
        userInfo: null,
        isLoading: false,
        loginFailedMessage: action.payload,
      };
    case LOGOUT_SUCCESS:
      return {
        ...state,
        userInfo: null,
      };
    case SET_TOAST:
      return {
        ...state,
        toast: false,
      };
    default:
      return state;
  }
};
