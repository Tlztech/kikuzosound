import {
  EXAM_LOADING,
  GET_EXAMS_SUCCESS,
  GET_EXAMS_FAILED,
  ADD_EXAMS_FAILED,
  ADD_EXAMS_SUCCESS,
  SET_MESSAGE,
  DELETE_EXAMS_FAILED,
  DELETE_EXAMS_SUCCESS,
  UPDATE_EXAMS_SUCCESS,
  UPDATE_EXAMS_FAILED,
  RESET_EXAM_MESSAGE,
} from "../constants/actiontypes";

const initialState = {
  isLoading: true,
  examList: null,
  message: null,
  totalPage: 0,
};

export default (state = initialState, action) => {
  switch (action.type) {
    case EXAM_LOADING:
      return {
        ...state,
        isLoading: true,
      };
    case GET_EXAMS_SUCCESS:
      return {
        ...state,
        examList: action.payload.result,
        totalPage: action.payload.total_page,
        isLoading: false,
      };
    case GET_EXAMS_FAILED:
      return {
        ...state,
        examList: null,
        isLoading: false,
        totalPage: 0,
      };
    case ADD_EXAMS_SUCCESS:
      return {
        ...state,
        isLoading: false,
        newExamResult: action.payload,
        message: null,
      };
    case SET_MESSAGE:
      return {
        ...state,
        message: action.payload,
      };
    case ADD_EXAMS_FAILED:
      return {
        ...state,
        isLoading: false,
        message: null,
      };
    case DELETE_EXAMS_FAILED:
      return {
        ...state,
        isLoading: false,
        message: null,
      };
    case DELETE_EXAMS_SUCCESS:
      return {
        ...state,
        isLoading: false,
        message: null,
      };
    case UPDATE_EXAMS_SUCCESS:
      return {
        ...state,
        isLoading: false,
        message: null,
      };
    case UPDATE_EXAMS_FAILED:
      return {
        ...state,
        isLoading: false,
        message: null,
      };
    case RESET_EXAM_MESSAGE:
      return {
        ...state,
        message: null,
      };
    default:
      return state;
  }
};
