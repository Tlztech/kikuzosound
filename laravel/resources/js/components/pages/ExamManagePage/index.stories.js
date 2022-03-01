import React from "react";
import ExamManagePage from "./index";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../../js/redux/store";

export default {
  title: "pages-ExamManagePage",
};

const data = {
  header: [
    "No",
    "Title",
    "Step of Exam",
    "Created",
    "Updated",
    "Enable/Disable",
    "Analytics",
    "Edit",
    "Delete",
  ],
  table_data: [
    {
      No: "1",
      Title: "test",
      Step_of_Exam: ["1.Quiz3 ", "2.Quiz4 ", "3.Quiz5 "],
      Created: "2020/03/34",
      Updated: "2020/03/34",
      Enable_Disable: "enable",
      Analytics: "test",
      Edit: "teste",
      Delete: "teset",
    },
    {
      No: "1",
      Title: "test",
      Step_of_Exam: ["1.Quiz3 ", "2.Quiz4 ", "3.Quiz5 "],
      Created: "2020/03/34",
      Updated: "2020/03/34",
      Enable_Disable: "enable",
      Analytics: "test",
      Edit: "teste",
      Delete: "teset",
    },
    {
      No: "1",
      Title: "test",
      Step_of_Exam: ["1.Quiz3 ", "2.Quiz4 ", "3.Quiz5 "],
      Created: "2020/03/34",
      Updated: "2020/03/34",
      Enable_Disable: "enable",
      Analytics: "test",
      Edit: "teste",
      Delete: "teset",
    },
  ],
};

const tasks = [
  {
    id: "1",
    taskName: "Quiz 1",
    type: "examLists",
  },
  {
    id: "2",
    taskName: "Quiz 2",
    type: "examLists",
  },
  {
    id: "3",
    taskName: "Quiz 3",
    type: "examSteps",
  },
  {
    id: "4",
    taskName: "Quiz 4",
    type: "examSteps",
  },
  {
    id: "5",
    taskName: "Quiz 5",
    type: "examSteps",
  },
];

storiesOf("pages-ExamManagePage", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("Exam Manage Page", () => <ExamManagePage data={data} tasks={tasks} />);
