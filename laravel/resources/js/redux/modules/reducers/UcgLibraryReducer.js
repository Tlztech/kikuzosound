import {
  UCG_LIBRARY_LOADING,
  GET_UCG_LIBRARY_SUCCESS,
  GET_UCG_LIBRARY_FAILED,
  ADD_UCG_LIBRARY_SUCCESS,
  ADD_UCG_LIBRARY_FAILED,
  UPDATE_UCG_SUCCESS,
  UPDATE_UCG_FAILED,
  DELETE_UCG_SUCCESS,
  DELETE_UCG_FAILED,
  SET_UCG_MESSAGE,
  RESET_UCG_MESSAGE,
} from "../constants/actiontypes";

const initialState = {
  isLoading: true,
  ucgList: null,
  ucg_message: null,
  totalPage: 0,
};

export default (state = initialState, action) => {
  switch (action.type) {
    case UCG_LIBRARY_LOADING:
      return {
        ...state,
        isLoading: true,
      };
    case GET_UCG_LIBRARY_SUCCESS:
      return {
        ...state,
        ucgList: action.payload.result,
        isLoading: false,
        totalPage: action.payload.total_page,
      };
    case GET_UCG_LIBRARY_FAILED:
      return {
        ...state,
        ucgList: null,
        isLoading: false,
        totalPage: 0,
      };
    case ADD_UCG_LIBRARY_SUCCESS:
      return {
        ...state,
        isLoading: false,
        ucgResult: action.payload,
        ucg_message: null,
      };

    case ADD_UCG_LIBRARY_FAILED:
      return {
        ...state,
        isLoading: false,
        ucg_message: null,
      };

    case DELETE_UCG_FAILED:
      return {
        ...state,
        isLoading: false,
        ucg_message: null,
      };
    case DELETE_UCG_SUCCESS:
      return {
        ...state,
        isLoading: false,
        ucg_message: null,
      };
    case UPDATE_UCG_SUCCESS:
      return {
        ...state,
        isLoading: false,
        ucg_message: null,
      };
    case UPDATE_UCG_FAILED:
      return {
        ...state,
        isLoading: false,
        ucg_message: null,
      };
    case SET_UCG_MESSAGE:
      return {
        ...state,
        ucg_message: action.payload,
      };
    case RESET_UCG_MESSAGE:
      return {
        ...state,
        ucg_message: null,
      };
    default:
      return state;
  }
};
