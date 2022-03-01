import {
  USER_LOADING,
  GET_USERS_SUCCESS,
  GET_USERS_FAILED,
  SET_USER_DISABLED_SUCCESS,
  SET_USER_DISABLED_FAILED,
  USER_DISABLE_LOADING,
} from "../constants/actiontypes";

const initialState = {
  isLoading: true,
  usersList: null,
  isUserDisableSuccess: false,
  userMessage: null,
  totalPage: null,
};

export default (state = initialState, action) => {
  switch (action.type) {
    case USER_LOADING:
      return {
        ...state,
        isLoading: true,
        isUserDisableSuccess: false,
      };
    case GET_USERS_SUCCESS:
      return {
        ...state,
        usersList: action.payload.result,
        totalPage: action.payload.total_page,
        isLoading: false,
      };
    case GET_USERS_FAILED:
      return {
        ...state,
        usersList: null,
        totalPage: null,
        isLoading: false,
      };
    case SET_USER_DISABLED_SUCCESS:
      const filteredList = state.usersList.filter(
        (item) => item.id !== action.payload.id
      );
      const processedList = [...filteredList, action.payload];
      return {
        ...state,
        isLoading: false,
        isUserDisableSuccess: true,
        usersList: processedList,
        userMessage: { mode: "success", content: "edit_user_status_success" },
      };
    case SET_USER_DISABLED_FAILED:
      return {
        ...state,
        isLoading: false,
        isUserDisableSuccess: false,
        userMessage: { mode: "error", content: "edit_user_status_failed" },
      };
    case USER_DISABLE_LOADING:
      return {
        ...state,
        userMessage: null,
      };
    default:
      return state;
  }
};
