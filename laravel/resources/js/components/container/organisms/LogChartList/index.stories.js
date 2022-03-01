import React from "react";
import { Provider } from "react-redux";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { createStore } from "../../../../redux/store";
import LogChartList from "./index";

const store = createStore();

const withProvider = (story) => (
  <MemoryRouter initialEntries={["/"]}>
    <Provider store={store}>{story()}</Provider>
  </MemoryRouter>
);

const spend_time_data_set = {
  labels: [
    "Auscultation sound",
    "Stethoscope",
    "Palpation content",
    "ECG",
    "Examination",
    "X-ray list",
    "Echocardiography ",
  ],
  datasets: [
    {
      data: [23, 24, 1, 2, 3, 5, 7],
      backgroundColor: [
        "#4285f4",
        "#ea4335",
        "#fbbc04",
        "#34a853",
        "#ff6d01",
        "#46bdc6",
        "#7baaf7",
      ],
      hoverBackgroundColor: [
        "#4285f4",
        "#ea4335",
        "#fbbc04",
        "#34a853",
        "#ff6d01",
        "#46bdc6",
        "#7baaf7",
      ],
    },
  ],
};

const ranking_data_set = {
  labels: ["100-90 point", "89-80 point", "11", "41", "49-30 point", " ", " "],
  datasets: [
    {
      data: [75, 50, 30, 100, 30, 25, 10],
      backgroundColor: [
        "#037bfc",
        "#fc1c03",
        "#fca903",
        "#2e9c16",
        "#de6926",
        "#26dec9",
        "#5e8bad",
      ],
      hoverBackgroundColor: [
        "#037bfc",
        "#fc1c03",
        "#fca903",
        "#2e9c16",
        "#de6926",
        "#26dec9",
        "#5e8bad",
      ],
    },
  ],
};

storiesOf("organism-LogChartList", module)
  .addDecorator(withProvider)
  .add("LogChartList", () => <LogChartList />);
