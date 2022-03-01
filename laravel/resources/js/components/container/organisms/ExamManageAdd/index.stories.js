import React from "react";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";

import store from "../../../../redux/store";
import ExamManageAdd from "./index";

const step_of_exam = [
  {
    id: 327,
    taskName: "1",
    taskName_EN: "1",
    type: "examLists",
  },
  {
    id: 326,
    taskName: "2",
    taskName_EN: "2",
    type: "examLists",
  },
  {
    id: 325,
    taskName: "3",
    taskName_EN: "3",
    type: "examLists",
  },
];

storiesOf("organisms-ExamManageAdd", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("Exam_Manage_Add", () => (
    <ExamManageAdd isVisible="true" id={1} exams={step_of_exam} />
  ));
