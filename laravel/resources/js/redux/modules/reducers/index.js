import { combineReducers } from "redux";
import { persistReducer } from "redux-persist";
import storage from "redux-persist/lib/storage";

import ExamReducer from "./ExamReducer";
import QuizReducer from "./QuizReducer";
import LoginReducer from "./LoginReducer";
import UserReducer from "./UserReducer";
import ExamGroupReducer from "./ExamGroupReducer";
import ExamResultsReducer from "./ExamResultsReducer";
import LogAnalyticsReducer from "./LogAnalyticsReducer";
import ResetPasswordReducer from "./ResetPasswordReducer";
import QuizPackReducer from "./QuizPackReducer";
import XrayLibraryReducer from "./XrayLibraryReducer";
import AusculaideLibraryReducer from "./AusculaideLibraryReducer";
import StethoLibraryReducer from "./StethoLibraryReducer";
import LibraryUserReducer from "./LibraryUserReducer";
import EcgLibraryReducer from "./EcgLibraryReducer";
import InspectionLibraryReducer from "./InspectionLibraryReducer";
import PalpationLibraryReducer from "./PalpationLibraryReducer";
import UcgLibraryReducer from "./UcgLibraryReducer";
import ChangePasswordReducer from "./ChangePasswordReducer";
import LogAnalyticReducer from "./LogAnalyticReducer";
import TableSort from "./TableSort";
import HomeReducer from "./HomeReducer";

const userPersistConfig = {
  key: "user",
  storage,
};

const appReducer = combineReducers({
  auth: persistReducer(userPersistConfig, LoginReducer),
  exams: ExamReducer,
  examGroup: ExamGroupReducer,
  quizzes: QuizReducer,
  userManagement: UserReducer,
  examResults: ExamResultsReducer,
  logAnalytics: LogAnalyticsReducer,
  resetPassword: ResetPasswordReducer,
  quizPackList: QuizPackReducer,
  LibraryUserList: LibraryUserReducer,
  ausculaideLibraryList: AusculaideLibraryReducer,
  stethoLibraryList: StethoLibraryReducer,
  xrayLibraryList: XrayLibraryReducer,
  ecgLibList: EcgLibraryReducer,
  inspectionLibList: InspectionLibraryReducer,
  palpationLibList: PalpationLibraryReducer,
  ucgLibrary: UcgLibraryReducer,
  tableSort: TableSort,
  changePassword: ChangePasswordReducer,
  logAnalytic: LogAnalyticReducer,
  HomeDataList: HomeReducer
});

const rootReducer = (state, action) => {
  if (action.type === "LOGOUT") {
    state = undefined;
  }

  return appReducer(state, action);
};

export default rootReducer;
