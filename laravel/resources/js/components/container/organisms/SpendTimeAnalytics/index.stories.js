import React from "react";
import SpendTimeAnalytics from "./index";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../../redux/store";

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

storiesOf("organisms-SpendTimeAnalytics", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("spend_time_analtyics", () => <SpendTimeAnalytics title="User Ratio" data={spend_time_data_set} />);
