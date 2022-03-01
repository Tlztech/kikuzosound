import {
  GET_LOGANALYTICSRESULT_SUCCESS_N,
  GET_LOGANALYTICSRESULT_FAILED_N,
  LOGANALYTICSRESULT_LOADING_N,
  FILTER_LOG_ANALYTICS_SUCCESS,
  FILTER_LOG_ANALYTICS_FAILED,
  GET_RANKING_DATA_SUCCESS,
  GET_RANKING_DATA_FAILED,
  GET_PIE_CHART_DATA_SUCCESS,
  GET_PIE_CHART_DATA_FAILED,
  GET_SELECT_MENU_LOADING,
  GET_SELECT_MENU_SUCCESS,
  GET_SELECT_MENU_FAILED,
} from "../constants/actiontypes";

const initialState = {
  isLoading: true,
  logAnalyticsList: null,
  filteredTargetList: null,
  rankingList: null,
  pieChartList: null,
  totalPage: null,
};

export default (state = initialState, action) => {
  switch (action.type) {
    case LOGANALYTICSRESULT_LOADING_N:
      return {
        ...state,
        isLoading: true,
      };
    case GET_LOGANALYTICSRESULT_SUCCESS_N:
      return {
        ...state,
        logAnalyticsList: action.payload.result,
        totalPage: action.payload.total_page,
        isLoading: false,
      };
    case GET_LOGANALYTICSRESULT_FAILED_N:
      return {
        ...state,
        logAnalyticsList: null,
        isLoading: false,
      };
    case FILTER_LOG_ANALYTICS_SUCCESS:
      return {
        ...state,
        filteredTargetList: action.payload,
        isLoading: false,
      };
    case FILTER_LOG_ANALYTICS_FAILED:
      return {
        ...state,
        filteredTargetList: null,
        isLoading: false,
      };
    case GET_RANKING_DATA_SUCCESS:
      return {
        ...state,
        rankingList: action.payload,
        isLoading: false,
      };
    case GET_RANKING_DATA_FAILED:
      return {
        ...state,
        rankingList: null,
        isLoading: false,
      };
    case GET_PIE_CHART_DATA_SUCCESS:
      return {
        ...state,
        pieChartList: action.payload,
        isLoading: false,
      };
    case GET_PIE_CHART_DATA_FAILED:
      return {
        ...state,
        pieChartList: null,
        isLoading: false,
      };
    case GET_SELECT_MENU_LOADING:
      return {
        ...state,
        isLoading: true,
      };
    case GET_SELECT_MENU_SUCCESS:
    
      let group = action.payload.univ.map( item => { return item.id })
      let quiz = action.payload.quiz.map( item => { 
        if( item.univ_id ){
          let quiz_univ = item.univ_id.split(",").map( i => parseInt(i) )
          item.univ_id = quiz_univ.filter( i => group.includes(i))
        }
        else item.univ_id = null
        return item
      })
      action.payload.quiz = quiz
      
      let exam = action.payload.exam.map( item => { 
        if( item.univ_id ){
          let exam_univ = item.univ_id.split(",").map( i => parseInt(i) )
          item.univ_id = exam_univ.filter( i => group.includes(i))
        }
        else item.univ_id = null
        return item
      })
      action.payload.exam = exam
      
      
      let library = action.payload.library.map( item => { 
        if( item.exam_groups.length >= 1){
          let exam_groups = item.exam_groups.map( i => { return i.id}).filter( i => group.includes(i))
          item.exam_groups = exam_groups
        } else item.exam_groups = null
        return item
      })
      action.payload.library = library
      
      
      return {
        ...state,
        logAnalyticMenu: action.payload,
        isLoading: false,
      };
    case GET_SELECT_MENU_FAILED:
      return {
        ...state,
        logAnalyticMenu: null,
        isLoading: false,
      };
    default:
      return false;
  }
};
