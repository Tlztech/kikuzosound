import React from "react";
import StethoSOunds from "./index";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../redux/store";

const data = {
  header: [
    "ID",
    "Title",
    "Auscultation sound type",
    "Auscultation site",
    "Normal abnormal",
    "status",
    "Supervisor",
    "Public / Private",
    "Update date and time"
  ],
  table_data: [{}]
};

storiesOf("pages-StethoSounds", module)
  .addDecorator(story => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("user_management", () => <StethoSOunds data={data} />);
