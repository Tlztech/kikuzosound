import {
  GET_EXAMRESULTS_SUCCESS,
  GET_EXAMRESULTS_FAILED,
  EXAMRESULTS_LOADING,
  GET_CHART_DATA_SUCCESS,
  GET_CHART_DATA_FAILED,
  GET_EXAM_ANAYTICS_MENU_LOADING,
  GET_EXAM_ANAYTICS_MENU_SUCCESS,
  GET_EXAM_ANAYTICS_MENU_FAILED,
} from "../constants/actiontypes";

const initialState = {
  isLoading: true,
  isExamMenuLoading: true,
  examResultList: null,
  chartDataList: null,
  examMenuList: null,
  totalPage: null,
};

export default (state = initialState, action) => {
  switch (action.type) {
    case EXAMRESULTS_LOADING:
      return {
        ...state,
        isLoading: true,
      };
    case GET_EXAMRESULTS_SUCCESS:
      return {
        ...state,
        examResultList: (action.payload && action.payload.res) ? action.payload.res : [],
        grouptList: (action.payload && action.payload.group_res) ? action.payload.group_res : [],
        totalPage: (action.payload && action.payload.total_page) ? action.payload.total_page : 0,
        isLoading: false,
      };
    case GET_EXAMRESULTS_FAILED:
      return {
        ...state,
        examResultList: null,
        grouptList: null,
        totalPage: null,
        isLoading: false,
      };
    case GET_CHART_DATA_SUCCESS:
      return {
        ...state,
        chartDataList: state.chartDataList
          ? {
              ...state.chartDataList,
              [action.payload.chartType]: action.payload.data.chart,
            }
          : { [action.payload.chartType]: action.payload.data.chart },
        isLoading: false,
      };
    case GET_CHART_DATA_FAILED:
      return {
        ...state,
        chartDataList: null,
        isLoading: false,
      };
    case GET_EXAM_ANAYTICS_MENU_LOADING:
      return {
        ...state,
        isExamMenuLoading: true,
      };
    case GET_EXAM_ANAYTICS_MENU_SUCCESS:
      let group = action.payload.group.map( item => { return item.group_id })
      let title = action.payload.title.map( item => { 
        if( item.univ_id ){
          let title_univ = item.univ_id.split(",").map( i => parseInt(i) )
          item.univ_id = title_univ.filter( i => group.includes(i))
        }
        else item.univ_id = null
        return item
      })
      action.payload.title = title
      return {
        ...state,
        examMenuList: action.payload,
        isExamMenuLoading: false,
      };
    case GET_EXAM_ANAYTICS_MENU_FAILED:
      return {
        ...state,
        examMenuList: null,
        isExamMenuLoading: false,
      };
    default:
      return state;
  }
};
