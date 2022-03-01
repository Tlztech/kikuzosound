import React from "react";
import LogAnalyticsTable from "./index";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../../redux/store";

const table_data = [
  {
    id: 1,
    univ: "Univ A",
    user: "User 1",
    exam_type: "Exam",
    exam: "Exam A",
    quiz: "",
    library: "",
    library_type: "",
    correct: "2",
  },
  {
    id: 2,
    univ: "Univ A",
    user: "User 1",
    exam_type: "Exam",
    exam: "Exam A",
    quiz: "Quiz 1",
    library: "",
    library_type: "",
    correct: "1",
  },
  {
    id: 3,
    univ: "Univ B",
    user: "User 2",
    exam_type: "Quiz",
    exam: "",
    quiz: "Quiz 2",
    library: "Library 3",
    library_type: "Inspection",
    correct: "",
  },
  {
    id: 4,
    univ: "Univ C",
    user: "User 3",
    exam_type: "Library",
    exam: "",
    quiz: "",
    library: "Library 5",
    library_type: "Ausculaide",
    correct: "",
  },
];

storiesOf("organism-logAnalyticsTable", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("log_analytics_table", () => (
    <LogAnalyticsTable tableData={table_data} />
  ));
