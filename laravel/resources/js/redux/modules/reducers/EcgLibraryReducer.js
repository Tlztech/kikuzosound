import {
  ECG_LIBRARY_LOADING,
  ADD_ECG_FAILED,
  ADD_ECG_SUCCESS,
  GET_ECG_LIBRARY_SUCCESS,
  GET_ECG_LIBRARY_FAILED,
  UPDATE_ECG_SUCCESS,
  UPDATE_ECG_FAILED,
  DELETE_ECG_SUCCESS,
  DELETE_ECG_FAILED,
  SET_ECG_MESSAGE,
  RESET_ECG_MESSAGE,
} from "../constants/actiontypes";

const initialState = {
  isLoading: true,
  ecg_list: null,
  ecg_message: null,
  totalPage: 0,
};

export default (state = initialState, action) => {
  switch (action.type) {
    case ECG_LIBRARY_LOADING:
      return {
        ...state,
        isLoading: true,
      };
    case GET_ECG_LIBRARY_SUCCESS:
      return {
        ...state,
        ecg_list: action.payload.result,
        totalPage: action.payload.total_page,
        isLoading: false,
      };
    case GET_ECG_LIBRARY_FAILED:
      return {
        ...state,
        ecg_list: null,
        isLoading: false,
        totalPage: null,
      };
    case ADD_ECG_SUCCESS:
      return {
        ...state,
        isLoading: false,
        newECGResult: action.payload,
        ecg_message: null,
      };

    case ADD_ECG_FAILED:
      return {
        ...state,
        isLoading: false,
        ecg_message: null,
      };
    case DELETE_ECG_FAILED:
      return {
        ...state,
        isLoading: false,
        ecg_message: null,
      };
    case DELETE_ECG_SUCCESS:
      return {
        ...state,
        isLoading: false,
        ecg_message: null,
      };
    case UPDATE_ECG_SUCCESS:
      return {
        ...state,
        isLoading: false,
        ecg_message: null,
      };
    case UPDATE_ECG_FAILED:
      return {
        ...state,
        isLoading: false,
        ecg_message: null,
      };
    case SET_ECG_MESSAGE:
      return {
        ...state,
        ecg_message: action.payload,
      };
    case RESET_ECG_MESSAGE:
      return {
        ...state,
        ecg_message: null,
      };
    default:
      return state;
  }
};
