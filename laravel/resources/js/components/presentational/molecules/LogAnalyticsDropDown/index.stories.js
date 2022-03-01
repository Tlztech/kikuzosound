import React from "react";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import { createStore } from "../../../../redux/store";
import LogAnalyticsDropDown from "./index";

const store = createStore();

storiesOf("molecules-LogAnalyticsDropDown", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("Log_Analytics_DropDown", () => <LogAnalyticsDropDown />);
