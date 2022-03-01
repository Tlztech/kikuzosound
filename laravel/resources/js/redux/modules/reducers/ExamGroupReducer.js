import {
  GET_EXAMGROUP_SUCCESS,
  GET_EXAMGROUP_FAILED,
  EXAMGROUP_LOADING,
} from "../constants/actiontypes";

const initialState = {
  isLoading: true,
  examGroupList: null,
};

export default (state = initialState, action) => {
  switch (action.type) {
    case EXAMGROUP_LOADING:
      return {
        ...state,
        isLoading: true,
      };
    case GET_EXAMGROUP_SUCCESS:
      return {
        ...state,
        examGroupList: action.payload,
        isLoading: false,
      };
    case GET_EXAMGROUP_FAILED:
      return {
        ...state,
        examGroupList: null,
        isLoading: false,
      };

    default:
      return state;
  }
};
