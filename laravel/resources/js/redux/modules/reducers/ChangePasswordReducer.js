import {
  CHANGE_PASSWORD_SUCCESS,
  CHANGE_PASSWORD_FAILED,
  CHANGE_PASSWORD_LOADING,
  SET_CHANGE_PASSWORD_MESSAGE,
} from "../constants/actiontypes";

const initialState = {
  isSuccess: false,
  message: null,
};

export default (state = initialState, action) => {
  switch (action.type) {
    case CHANGE_PASSWORD_LOADING:
      return {
        ...state,
        isSuccess: false,
        message: null,
      };
    case CHANGE_PASSWORD_SUCCESS:
      return {
        ...state,
        isSuccess: true,
        message: null,
      };
    case CHANGE_PASSWORD_FAILED:
      return {
        ...state,
        isSuccess: false,
        message: action.payload,
      };
    case SET_CHANGE_PASSWORD_MESSAGE:
      return {
        ...state,
        message: action.payload,
      };
    default:
      return state;
  }
};
