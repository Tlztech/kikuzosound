import {
  INSPECTION_LIBRARY_LOADING,
  GET_INSPECTION_LIBRARY_SUCCESS,
  GET_INSPECTION_LIBRARY_FAILED,
  ADD_INSPECTION_FAILED,
  ADD_INSPECTION_SUCCESS,
  SET_INSPECTION_MESSAGE,
  DELETE_INSPECTION_LIBRARY_SUCCESS,
  DELETE_INSPECTION_LIBRARY_FAILED,
  UPDATE_INSPECTION_SUCCESS,
  UPDATE_INSPECTION_FAILED,
  RESET_INSPECTION_MESSAGE,
  RESET_INSPECTION_SUCCESS,
} from "../constants/actiontypes";

const initialState = {
  isLoading: true,
  inspectionLibraryList: null,
  inspection_message: null,
  addedItem: null,
  totalPage: 0,
};

export default (state = initialState, action) => {
  switch (action.type) {
    case INSPECTION_LIBRARY_LOADING:
      return {
        ...state,
        isLoading: true,
      };
    case GET_INSPECTION_LIBRARY_SUCCESS:
      return {
        ...state,
        inspectionLibraryList: action.payload.result,
        isLoading: false,
        totalPage: action.payload.total_page,
      };
    case GET_INSPECTION_LIBRARY_FAILED:
      return {
        ...state,
        inspectionLibraryList: null,
        isLoading: false,
        totalPage: 0,
      };
    case ADD_INSPECTION_SUCCESS:
      return {
        ...state,
        isLoading: false,
        inspectionLibraryList: [
          action.payload.inspection_item,
          ...state.inspectionLibraryList,
        ],
        addedItem: action.payload.id,
        inspection_message: null,
      };
    case ADD_INSPECTION_FAILED:
      return {
        ...state,
        isLoading: false,
      };
    case DELETE_INSPECTION_LIBRARY_FAILED:
      return {
        ...state,
        isLoading: false,
        inspection_message: null,
      };
    case DELETE_INSPECTION_LIBRARY_SUCCESS:
      return {
        ...state,
        isLoading: false,
        inspection_message: null,
      };
    case SET_INSPECTION_MESSAGE:
      return {
        ...state,
        inspection_message: action.payload,
      };
    case UPDATE_INSPECTION_SUCCESS:
      return {
        ...state,
        isLoading: false,
        inspection_message: null,
        addedItem: action.payload.id,
      };
    case UPDATE_INSPECTION_FAILED:
      return {
        ...state,
        isLoading: false,
        inspection_message: null,
      };
    case RESET_INSPECTION_MESSAGE:
      return {
        ...state,
        inspection_message: null,
        addedItem: null,
      };
    case RESET_INSPECTION_SUCCESS:
      return {
        ...state,
        addedItem: null,
      };
    default:
      return state;
  }
};
