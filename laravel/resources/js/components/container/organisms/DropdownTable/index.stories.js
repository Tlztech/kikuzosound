import React from "react";
import DropdownTable from "./index";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../../redux/store";

const table_header = [
  "id",
  "exam",
  "quiz",
  "user",
  "start datetime",
  "end datetime",
  "used time",
  "answer",
  "ok or miss",
];

const data = {
  table_data: [
    {
      id: "1",
      exam_group: "Group A",
      exam: "Exam A",
      user: "User AA",
      start_datetime: "2020/12/10 20:20:10",
      end_datetime: "2020/12/10 20:20:20",
      used_time: "0:10",
      answer: 3,
      miss_ok: "ok",
      exam_data: {
        id: 1,
      },
      user_data: {
        id: 1,
      },
    },
    {
      id: "2",
      exam_group: "Group A",
      exam: "Exam B",
      user: "User BB",
      start_datetime: "2020/12/10 20:20:10",
      end_datetime: "2020/12/10 20:20:20",
      used_time: "0:12",
      answer: 4,
      miss_ok: "miss",
      exam_data: {
        id: 2,
      },
      user_data: {
        id: 2,
      },
    },
    {
      id: "2",
      exam_group: "Group B",
      exam: "Exam A",
      user: "User AA",
      start_datetime: "2020/12/10 20:20:10",
      end_datetime: "2020/12/10 20:20:20",
      used_time: "0:13",
      answer: 3,
      miss_ok: "ok",
      exam_data: {
        id: 3,
      },
      user_data: {
        id: 3,
      },
    },
    {
      id: "3",
      exam_group: "Group B",
      exam: "Exam B",
      user: "User BB",
      start_datetime: "2020/12/10 20:20:10",
      end_datetime: "2020/12/10 20:20:20",
      used_time: "0:13",
      answer: 8,
      miss_ok: "miss",
      exam_data: {
        id: 4,
      },
      user_data: {
        id: 4,
      },
    },
  ],
};

const updatingChart = () => {
  console.log("updating");
};

storiesOf("Organisms-DropdownTable", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("dropdown_table", () => (
    <DropdownTable
      table_header={table_header}
      data={data}
      updateChart={() => updatingChart()}
      t={()=>{}}
    />
  ));
