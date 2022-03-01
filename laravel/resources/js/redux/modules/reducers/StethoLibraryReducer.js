import {
  STETHO_LIBRARY_LOADING,
  ADD_STETHO_FAILED,
  ADD_STETHO_SUCCESS,
  GET_STETHO_LIBRARY_SUCCESS,
  GET_STETHO_LIBRARY_FAILED,
  UPDATE_STETHO_SUCCESS,
  UPDATE_STETHO_FAILED,
  DELETE_STETHO_SUCCESS,
  DELETE_STETHO_FAILED,
  SET_STETHO_MESSAGE,
  RESET_STETHO_MESSAGE,
} from "../constants/actiontypes";

const initialState = {
  isLoading: true,
  stethoList: null,
  stetho_message: null,
  totalPage: 0,
};

export default (state = initialState, action) => {
  switch (action.type) {
    case STETHO_LIBRARY_LOADING:
      return {
        ...state,
        isLoading: true,
      };
    case GET_STETHO_LIBRARY_SUCCESS:
      return {
        ...state,
        stethoList: action.payload.result,
        totalPage: action.payload.total_page,
        isLoading: false,
      };
    case GET_STETHO_LIBRARY_FAILED:
      return {
        ...state,
        stethoList: null,
        isLoading: false,
        totalPage: null,
      };
    case ADD_STETHO_SUCCESS:
      return {
        ...state,
        isLoading: false,
        newstethoResult: action.payload,
        stetho_message: null,
      };

    case ADD_STETHO_FAILED:
      return {
        ...state,
        isLoading: false,
        stetho_message: null,
      };
    case DELETE_STETHO_FAILED:
      return {
        ...state,
        isLoading: false,
        stetho_message: null,
      };
    case DELETE_STETHO_SUCCESS:
      return {
        ...state,
        isLoading: false,
        stetho_message: null,
      };
    case UPDATE_STETHO_SUCCESS:
      return {
        ...state,
        isLoading: false,
        stetho_message: null,
      };
    case UPDATE_STETHO_FAILED:
      return {
        ...state,
        isLoading: false,
        stetho_message: null,
      };
    case SET_STETHO_MESSAGE:
      return {
        ...state,
        stetho_message: action.payload,
      };
    case RESET_STETHO_MESSAGE:
      return {
        ...state,
        stetho_message: null,
      };
    default:
      return state;
  }
};
