import {
  LIB_USER_LOADING,
  GET_LIB_USER_SUCCESS,
  GET_LIB_USER_FAILED,
} from "../constants/actiontypes";

const initialState = {
  isLoading: true,
  user_list : null
};

export default (state = initialState, action) => {
  switch (action.type) {
    case LIB_USER_LOADING:
      return {
        ...state,
        isLoading: true,
      };
    case GET_LIB_USER_SUCCESS:
      return {
        ...state,
        user_list: action.payload,
        isLoading: false,
      };
    case GET_LIB_USER_FAILED:
      return {
        ...state,
        user_list: null,
        isLoading: false,
      };
   
    default:
      return state;
  }
};
