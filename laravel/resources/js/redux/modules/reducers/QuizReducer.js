import {
  QUIZ_LOADING,
  GET_QUIZ_SUCCESS,
  GET_QUIZ_FAILED,
  GET_ALL_QUIZZES_SUCCESS,
  GET_ALL_QUIZZES_FAILED,
  ADD_QUIZ_SUCCESS,
  ADD_QUIZ_FAILED,
  DELETE_QUIZ_SUCCESS,
  DELETE_QUIZ_FAILED,
  SET_QUIZ_MESSAGE,
  RESET_QUIZ_MESSAGE,
  UPDATE_QUIZ_SUCCESS,
  UPDATE_QUIZ_FAILED,
  GET_SINGLE_QUIZ_SUCCESS,
  GET_SINGLE_QUIZ_FAILED,
} from "../constants/actiontypes";

const initialState = {
  isLoading: true,
  quizList: null,
  allQuizzesList: null,
  quizzes_message: "",
  updatedItem: null,
  singleQuiz: null,
  totalPage: 0,
};

export default (state = initialState, action) => {
  switch (action.type) {
    case QUIZ_LOADING:
      return {
        ...state,
        isLoading: true,
      };
    case GET_QUIZ_SUCCESS:
      return {
        ...state,
        quizList: action.payload,
        isLoading: false,
      };
    case GET_QUIZ_FAILED:
      return {
        ...state,
        quizList: null,
        isLoading: false,
      };
    case GET_ALL_QUIZZES_SUCCESS:
      return {
        ...state,
        allQuizzesList: action.payload.result,
        totalPage: action.payload.total_page,
        isLoading: false,
      };
    case GET_ALL_QUIZZES_FAILED:
      return {
        ...state,
        allQuizzesList: null,
        totalPage: null,
        isLoading: false,
      };

    case ADD_QUIZ_SUCCESS:
      return {
        ...state,
        isLoading: false,
        newQuizzesResult: action.payload,
        quizzes_message: null,
      };

    case ADD_QUIZ_FAILED:
      return {
        ...state,
        isLoading: false,
        quizzes_message: null,
      };

    case DELETE_QUIZ_FAILED:
      return {
        ...state,
        isLoading: false,
        quizzes_message: null,
      };
    case DELETE_QUIZ_SUCCESS:
      return {
        ...state,
        isLoading: false,
        quizzes_message: null,
      };
    case UPDATE_QUIZ_SUCCESS:
      const unchangedItems = state.allQuizzesList.filter(
        (item) => item.id !== action.payload.quizzes.id
      );
      return {
        ...state,
        isLoading: false,
        quizzes_message: null,
        allQuizzesList: [action.payload.quizzes, ...unchangedItems],
        updatedItem: action.payload.quizzes.id,
      };
    case UPDATE_QUIZ_FAILED:
      return {
        ...state,
        isLoading: false,
        quizzes_message: null,
      };
    case SET_QUIZ_MESSAGE:
      return {
        ...state,
        quizzes_message: action.payload,
      };
    case RESET_QUIZ_MESSAGE:
      return {
        ...state,
        quizzes_message: null,
        updatedItem: null,
      };
    case GET_SINGLE_QUIZ_SUCCESS:
      return {
        ...state,
        singleQuiz: action.payload,
        isLoading: false,
      };
    case GET_SINGLE_QUIZ_FAILED:
      return {
        ...state,
        singleQuiz: null,
        isLoading: false,
      };
    default:
      return state;
  }
};
