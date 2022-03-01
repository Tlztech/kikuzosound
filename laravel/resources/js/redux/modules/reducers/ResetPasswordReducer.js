import {
  GET_RESETPASSWORD_SUCCESS,
  GET_RESETPASSWORD_FAILED,
  SET_NEW_PASSWORD_SUCCESS,
  SET_NEW_PASSWORD_FAILED,
} from "../constants/actiontypes";

const initialState = {
  isSuccess: false,
  response_msg : null
};

export default (state = initialState, action) => {
  switch (action.type) {
    case GET_RESETPASSWORD_SUCCESS:
      return {
        ...state,
        isSuccess: true,
        response_msg : action.payload
      };
    case GET_RESETPASSWORD_FAILED:
      return {
        ...state,
        isSuccess: false,
        response_msg : action.payload
      };
    case SET_NEW_PASSWORD_SUCCESS:
      return {
        ...state,
        isSuccess: true,
        response_msg : action.payload
      };
    case SET_NEW_PASSWORD_FAILED:
      return {
        ...state,
        isSuccess: false,
        response_msg : action.payload
      };

    default:
      return state;
  }
};
