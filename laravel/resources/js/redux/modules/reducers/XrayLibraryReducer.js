import {
  XRAY_LIBRARY_LOADING,
  GET_XRAY_LIBRARY_SUCCESS,
  GET_XRAY_LIBRARY_FAILED,
  ADD_XRAY_SUCCESS,
  ADD_XRAY_FAILED,
  UPDATE_XRAY_SUCCESS,
  UPDATE_XRAY_FAILED,
  DELETE_XRAY_SUCCESS,
  DELETE_XRAY_FAILED,
  SET_XRAY_MESSAGE,
  RESET_XRAY_MESSAGE,
} from "../constants/actiontypes";

const initialState = {
  isLoading: true,
  xrayList: null,
  xray_message: null,
  addedItem: null,
  totalPage: 0,
};

export default (state = initialState, action) => {
  switch (action.type) {
    case XRAY_LIBRARY_LOADING:
      return {
        ...state,
        isLoading: true,
      };
    case GET_XRAY_LIBRARY_SUCCESS:
      return {
        ...state,
        xrayList: action.payload.result,
        totalPage: action.payload.total_page,
        isLoading: false,
      };
    case GET_XRAY_LIBRARY_FAILED:
      return {
        ...state,
        xrayList: null,
        isLoading: false,
        totalPage: null,
      };
    case ADD_XRAY_SUCCESS:
      return {
        ...state,
        isLoading: false,
        newXrayResult: action.payload,
        xray_message: null,
        addedItem: action.payload.id,
      };
    case ADD_XRAY_FAILED:
      return {
        ...state,
        isLoading: false,
        xray_message: null,
      };
    case UPDATE_XRAY_SUCCESS:
      return {
        ...state,
        isLoading: false,
        xray_message: null,
        addedItem: action.payload.id,
      };
    case UPDATE_XRAY_FAILED:
      return {
        ...state,
        isLoading: false,
        xray_message: null,
      };
    case DELETE_XRAY_FAILED:
      return {
        ...state,
        isLoading: false,
        xray_message: null,
      };
    case DELETE_XRAY_SUCCESS:
      return {
        ...state,
        isLoading: false,
        xray_message: null,
      };
    case SET_XRAY_MESSAGE:
      return {
        ...state,
        xray_message: action.payload,
      };
    case RESET_XRAY_MESSAGE:
      return {
        ...state,
        xray_message: null,
        addedItem: null,
      };
    default:
      return state;
  }
};
