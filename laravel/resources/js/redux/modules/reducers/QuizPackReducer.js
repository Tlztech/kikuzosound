import {
  QUIZPACK_LOADING,
  QUIZPACKINDEX_LOADING,
  GET_QUIZPACK_SUCCESS,
  GET_QUIZPACKINDEX_SUCCESS,
  GET_QUIZPACK_FAILED,
  GET_QUIZPACKINDEX_FAILED,
  ADD_QUIZPACK_SUCCESS,
  ADD_QUIZPACK_FAILED,
  UPDATE_QUIZPACK_SUCCESS,
  UPDATE_QUIZPACK_FAILED,
  DELETE_QUIZPACK_SUCCESS,
  DELETE_QUIZPACK_FAILED,
  SET_QUIZPACK_MESSAGE,
  RESET_QUIZPACK_MESSAGE,
  GET_SINGLE_QUIZPACK_SUCCESS,
  GET_SINGLE_QUIZPACK_FAILED,
} from "../constants/actiontypes";

const initialState = {
  isLoading: true,
  quizPackList: null,
  quizpack_msg: null,
  singleQuizPack: null,
  totalPage: 0,
};

export default (state = initialState, action) => {
  switch (action.type) {
    case QUIZPACK_LOADING:
      return {
        ...state,
        isLoading: true,
        singleQuizPack: null,
      };
    case QUIZPACKINDEX_LOADING:
      return {
        ...state,
        isLoading: true,
      };
    case GET_QUIZPACK_SUCCESS:
      return {
        ...state,
        quizPackList: action.payload.result,
        totalPage: action.payload.total_page,
        isLoading: false,
      };
    case GET_QUIZPACKINDEX_SUCCESS:
      return {
        ...state,
        quizPackListIndex: action.payload,
        isLoading: false,
      };
    case GET_QUIZPACK_FAILED:
      return {
        ...state,
        quizPackList: null,
        isLoading: false,
      };
    case GET_QUIZPACKINDEX_FAILED:
      return {
        ...state,
        quizPackListIndex: null,
        isLoading: false,
      };
    case ADD_QUIZPACK_SUCCESS:
      return {
        ...state,
        isLoading: false,
        newQUIZPACKResult: action.payload,
        quizpack_msg: null,
      };
    case ADD_QUIZPACK_FAILED:
      return {
        ...state,
        isLoading: false,
        quizpack_msg: null,
      };

    case DELETE_QUIZPACK_FAILED:
      return {
        ...state,
        isLoading: false,
        quizpack_msg: null,
      };
    case DELETE_QUIZPACK_SUCCESS:
      return {
        ...state,
        isLoading: false,
        quizpack_msg: null,
      };
    case UPDATE_QUIZPACK_SUCCESS:
      return {
        ...state,
        isLoading: false,
        quizpack_msg: null,
      };
    case UPDATE_QUIZPACK_FAILED:
      return {
        ...state,
        isLoading: false,
        quizpack_msg: null,
      };
    case SET_QUIZPACK_MESSAGE:
      return {
        ...state,
        quizpack_msg: action.payload,
      };
    case RESET_QUIZPACK_MESSAGE:
      return {
        ...state,
        quizpack_msg: null,
      };
    case GET_SINGLE_QUIZPACK_SUCCESS:
      return {
        ...state,
        singleQuizPack: action.payload,
        isLoading: false,
      };
    case GET_SINGLE_QUIZPACK_FAILED:
      return {
        ...state,
        singleQuizPack: null,
        isLoading: false,
      };
    default:
      return state;
  }
};
