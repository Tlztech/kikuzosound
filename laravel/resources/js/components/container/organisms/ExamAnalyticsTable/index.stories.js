import React from "react";
import ExamAnalyticsTable from "./index";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../../redux/store";

const table_data = [
  {
    id: 1,
    univName: "Univ A",
    userName: "User 1",
    examName: "Common Exam 1",
    numberOfQuiz: "4",
    numberOfCorrect: "3",
    usedTime: "0:10",
  },
  {
    id: 2,
    univName: "Univ C",
    userName: "User 2",
    examName: "Exam 2",
    numberOfQuiz: "12",
    numberOfCorrect: "5",
    usedTime: "5:30",
  },
  {
    id: 3,
    univName: "Univ B",
    userName: "User 3",
    examName: "Common Exam 3",
    numberOfQuiz: "4",
    numberOfCorrect: "3",
    usedTime: "8:10",
  },
  {
    id: 4,
    univName: "Univ D",
    userName: "User 4",
    examName: "Common Exam 2",
    numberOfQuiz: "14",
    numberOfCorrect: "13",
    usedTime: "10:10",
  },
];

storiesOf("organisms-ExamAnalyticsTable", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("exam_analytics_table", () => (
    <ExamAnalyticsTable tableData={table_data} />
  ));
