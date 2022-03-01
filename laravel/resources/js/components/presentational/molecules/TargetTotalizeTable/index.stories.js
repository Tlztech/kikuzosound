import React from "react";
import TargetTotalizeTable from "./index";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../../redux/store";

const table_data = [
  {
    id: 1,
    title: "Number of lines",
    filteredValue: "16",
    allOfOwnData: "16",
    allOfSystemData: "16",
  },
  {
    id: 2,
    title: "Number of universities",
    filteredValue: "2",
    allOfOwnData: "2",
    allOfSystemData: "2",
  },
  {
    id: 3,
    title: "Number of users",
    filteredValue: "3",
    allOfOwnData: "3",
    allOfSystemData: "3",
  },
  {
    id: 4,
    title: "Total usage time",
    filteredValue: "1:20:30",
    allOfOwnData: "2:24:43",
    allOfSystemData: "10:20:37",
  },
  {
    id: 5,
    title: "Average usage time",
    filteredValue: "1:20:30",
    allOfOwnData: "2:24:43",
    allOfSystemData: "10:20:37",
  },
  {
    id: 6,
    title: "Number of tests",
    filteredValue: "10",
    allOfOwnData: "20",
    allOfSystemData: "100",
  },
  {
    id: 7,
    title: "Exam correct answer rate",
    filteredValue: "20%",
    allOfOwnData: "80%",
    allOfSystemData: "40%",
  },
  {
    id: 8,
    title: "Total test usage time",
    filteredValue: "1:20:30",
    allOfOwnData: "2:24:43",
    allOfSystemData: "10:20:37",
  },
  {
    id: 9,
    title: "Test average usage time",
    filteredValue: "1:20:30",
    allOfOwnData: "2:24:43",
    allOfSystemData: "10:20:37",
  },
  {
    id: 10,
    title: "Number of quizzes",
    filteredValue: "10",
    allOfOwnData: "40",
    allOfSystemData: "300",
  },
];

storiesOf("molecule-targetTotalizeTable", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("target_totalize_table", () => (
    <TargetTotalizeTable tableData={table_data} />
  ));
