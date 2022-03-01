import {
  PALPATION_LIBRARY_LOADING,
  GET_PALPATION_LIBRARY_SUCCESS,
  GET_PALPATION_LIBRARY_FAILED,
  ADD_PALPATION_LIBRARY_SUCCESS,
  ADD_PALPATION_LIBRARY_FAILED,
  SET_PALPATION_MESSAGE,
  RESET_PALPATION_MESSAGE,
  UPDATE_PALPATION_SUCCESS,
  UPDATE_PALPATION_FAILED,
  DELETE_PALPATION_SUCCESS,
  DELETE_PALPATION_FAILED,
} from "../constants/actiontypes";

const initialState = {
  isLoading: true,
  palpation_list: null,
  palpation_message: null,
  totalPage: 0,
};

export default (state = initialState, action) => {
  switch (action.type) {
    case PALPATION_LIBRARY_LOADING:
      return {
        ...state,
        isLoading: true,
      };
    case GET_PALPATION_LIBRARY_SUCCESS:
      return {
        ...state,
        palpation_list: action.payload.result,
        totalPage: action.payload.total_page,
        isLoading: false,
      };
    case GET_PALPATION_LIBRARY_FAILED:
      return {
        ...state,
        palpation_list: null,
        totalPage: null,
        isLoading: false,
      };

    case ADD_PALPATION_LIBRARY_SUCCESS:
      return {
        ...state,
        isLoading: false,
        palpation_message: null,
      };
    case ADD_PALPATION_LIBRARY_FAILED:
      return {
        ...state,
        isLoading: false,
        palpation_message: null,
      };

    case DELETE_PALPATION_FAILED:
      return {
        ...state,
        isLoading: false,
        palpation_message: null,
      };
    case DELETE_PALPATION_SUCCESS:
      return {
        ...state,
        isLoading: false,
        palpation_message: null,
      };

    case UPDATE_PALPATION_SUCCESS:
      return {
        ...state,
        isLoading: false,
        palpation_message: null,
      };
    case UPDATE_PALPATION_FAILED:
      return {
        ...state,
        isLoading: false,
        palpation_message: null,
      };

    case SET_PALPATION_MESSAGE:
      return {
        ...state,
        palpation_message: action.payload,
      };

    case RESET_PALPATION_MESSAGE:
      return {
        ...state,
        palpation_message: null,
      };

    default:
      return state;
  }
};
