import {
  AUSCULAIDE_LIBRARY_LOADING,
  GET_AUSCULAIDE_LIBRARY_SUCCESS,
  GET_AUSCULAIDE_LIBRARY_FAILED,
  ADD_AUSCULAIDE_FAILED,
  ADD_AUSCULAIDE_SUCCESS,
  SET_AUSCULIADE_MESSAGE,
  DELETE_AUSCULAIDE_FAILED,
  DELETE_AUSCULAIDE_SUCCESS,
  UPDATE_AUSCULAIDE_SUCCESS,
  UPDATE_AUSCULAIDE_FAILED,
  RESET_AUSCULAIDE_MESSAGE,
} from "../constants/actiontypes";

const initialState = {
  isLoading: true,
  ausculaideList: null,
  ausc_message: null,
  totalPage: 0,
};

export default (state = initialState, action) => {
  switch (action.type) {
    case AUSCULAIDE_LIBRARY_LOADING:
      return {
        ...state,
        isLoading: true,
      };
    case GET_AUSCULAIDE_LIBRARY_SUCCESS:
      return {
        ...state,
        ausculaideList: action.payload.result,
        totalPage: action.payload.total_page,
        isLoading: false,
      };
    case GET_AUSCULAIDE_LIBRARY_FAILED:
      return {
        ...state,
        ausculaideList: null,
        isLoading: false,
        totalPage: null,
      };
    case ADD_AUSCULAIDE_SUCCESS:
      return {
        ...state,
        isLoading: false,
        newAusculaideResult: action.payload,
        ausc_message: null,
      };

    case ADD_AUSCULAIDE_FAILED:
      return {
        ...state,
        isLoading: false,
        ausc_message: null,
      };
    case DELETE_AUSCULAIDE_FAILED:
      return {
        ...state,
        isLoading: false,
        ausc_message: null,
      };
    case DELETE_AUSCULAIDE_SUCCESS:
      return {
        ...state,
        isLoading: false,
        ausc_message: null,
      };
    case UPDATE_AUSCULAIDE_SUCCESS:
      return {
        ...state,
        isLoading: false,
        ausc_message: null,
      };
    case UPDATE_AUSCULAIDE_FAILED:
      return {
        ...state,
        isLoading: false,
        ausc_message: null,
      };
    case SET_AUSCULIADE_MESSAGE:
      return {
        ...state,
        ausc_message: action.payload,
      };
    case RESET_AUSCULAIDE_MESSAGE:
      return {
        ...state,
        ausc_message: null,
      };
    default:
      return state;
  }
};
