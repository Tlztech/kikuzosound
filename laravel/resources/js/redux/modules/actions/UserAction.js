import Api from "../../../common/Api";
import {
  USER_LOADING,
  GET_USERS_SUCCESS,
  GET_USERS_FAILED,
  GET_SORT_TABLE_SUCCESS,
  UPDATE_SORT_TABLE_FAILED,
  GET_SORT_TABLE_FAILED,
  UPDATE_SORT_TABLE_SUCCESS,
  USER_SORT_TABLE_LOADING,
  SET_USER_DISABLED_SUCCESS,
  SET_USER_DISABLED_FAILED,
  USER_DISABLE_LOADING,
} from "../constants/actiontypes";

export const getAllUsers = (token, pagination, enabled, search="") => {
  return async (dispatch) => {
    try {
      dispatch(setUserLoading());
      const response = await Api.getAllUsers(token, pagination, enabled, search);
      if (response && response.data.success === "ok") {
        dispatch(getUsersSuccess(response.data));
      } else {
        dispatch(getUsersFailed());
      }
    } catch (e) {
      dispatch(getUsersFailed());
      console.log("get users failed", e);
    }
  };
};

export const getTableOrder = (token, info) => {
  return async (dispatch) => {
    try {
      dispatch(tableOrderLoading());
      const response = await Api.getUserTableSort(info, token);
      if (response && response.data.success === "ok") {
        dispatch(getUserTableSuccess(response.data.result));
      } else {
        dispatch(getSortTableFailed());
      }
    } catch (e) {
      dispatch(getSortTableFailed());
      console.log("getTableOrder fail", e);
    }
  };
};

export const updateSort = (data, info, token) => {
  return async (dispatch) => {
    try {
      dispatch(tableOrderLoading());
      const response = await Api.updateUserTableSort(data, info, token);
      if (response && response.data.success === "ok") {
        dispatch(userTableSuccess(response.data.result));
      } else {
        dispatch(updateSortTableFailed());
      }
    } catch (e) {
      dispatch(updateSortTableFailed());
      console.log("updateSort fail", e);
    }
  };
};

export const getAllUsersByExamGroup = (token, examGroupdId) => {
  return async (dispatch) => {
    try {
      dispatch(setUserLoading());
      const response = await Api.getAllUsersByExamGroup(token, examGroupdId);
      if (response && response.data.success === "ok") {
        dispatch(getUsersSuccess(response.data.result));
      } else {
        dispatch(getUsersFailed());
      }
    } catch (e) {
      dispatch(getUsersFailed());
      console.log("get users failed", e);
    }
  };
};

export function setUserLoading() {
  return {
    type: USER_LOADING,
  };
}

export function tableOrderLoading() {
  return {
    type: USER_SORT_TABLE_LOADING,
  };
}

export function getUsersSuccess(data) {
  return {
    type: GET_USERS_SUCCESS,
    payload: data,
  };
}

export function getUsersFailed() {
  return {
    type: GET_USERS_FAILED,
  };
}

export function getUserTableSuccess(data) {
  return {
    type: GET_SORT_TABLE_SUCCESS, //get sort table data
    payload: data,
  };
}

export function updateSortTableFailed() {
  return {
    type: UPDATE_SORT_TABLE_FAILED, //update sort table failed
  };
}

export function getSortTableFailed() {
  return {
    type: GET_SORT_TABLE_FAILED, // get sort data failed
  };
}

export function userTableSuccess(data) {
  return {
    type: UPDATE_SORT_TABLE_SUCCESS,
    payload: data,
  };
}

export function setUserDisableLoading() {
  return {
    type: USER_DISABLE_LOADING,
  };
}

export const setUserDisabled = (userId, isEnabled, token) => {
  return async (dispatch) => {
    try {
      dispatch(setUserDisableLoading());
      const response = await Api.setUserDisabled(userId, isEnabled, token);
      if (response && response.data.success === "ok") {
        dispatch(setUserDisabledSuccess(response.data.result));
      } else {
        dispatch(setUserDisabledFailed());
      }
    } catch (e) {
      dispatch(setUserDisabledFailed());
      console.log("user disabled failed", e);
    }
  };
};

export function setUserDisabledSuccess(data) {
  return {
    type: SET_USER_DISABLED_SUCCESS,
    payload: data,
  };
}

export function setUserDisabledFailed() {
  return {
    type: SET_USER_DISABLED_FAILED,
  };
}
