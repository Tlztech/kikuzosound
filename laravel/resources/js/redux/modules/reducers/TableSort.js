import {
    GET_SORT_TABLE_SUCCESS,
    UPDATE_SORT_TABLE_FAILED,
    GET_SORT_TABLE_FAILED,
    UPDATE_SORT_TABLE_SUCCESS,
    USER_SORT_TABLE_LOADING,
  } from "../constants/actiontypes";
  
  const initialState = {
    isLoading: true,
    tableSort: null,
    message: null,
  };
  
  export default (state = initialState, action) => {
    switch (action.type) {
      case USER_SORT_TABLE_LOADING:
        return {
          ...state,
          isLoading: true,
        };
      case GET_SORT_TABLE_SUCCESS:
        return {
          ...state,
          tableSort : action.payload,
          isLoading: false,
        };
        
      case UPDATE_SORT_TABLE_FAILED:
        return {
          ...state,
          isLoading: true,
        };
        
      case GET_SORT_TABLE_FAILED:
        return {
          ...state,
          isLoading: true,
        };
        
      case UPDATE_SORT_TABLE_SUCCESS:
        return {
          ...state,
          tableSort : action.payload,
          isLoading: false,
        };
      default:
        return state;
    }
  };
  