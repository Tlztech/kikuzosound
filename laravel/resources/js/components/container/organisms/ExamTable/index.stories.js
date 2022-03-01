import React from "react";
import ExamTable from "./index";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import { storiesOf } from "@storybook/react";
import store from "../../../../redux/store";

export default {
  title: "organisms-ExamTable",
};
const header = [
  "No",
  "Title",
  "Step of Exam",
  "Created",
  "Updated",
  "Enable/Disable",
  "Analytics",
  "Edit",
  "Delete",
];

const data = {
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
      examId: [0],
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
      examId: [100],
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
      examId: [200],
    },
  ],
};

storiesOf("organisms-ExamTable", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("exam_table", () => <ExamTable header={header} data={data} />);
