import React from "react";
import { Provider } from "react-redux";
import { storiesOf } from "@storybook/react";
import { MemoryRouter, BrowserRouter } from "react-router-dom";
import { createStore } from "../../../../redux/store";
import LogAnalyticsDropdown from "./index";

const store = createStore();

const withProvider = (story) => (
  <MemoryRouter initialEntries={["/"]}>
    <Provider store={store}>{story()}</Provider>
  </MemoryRouter>
);

storiesOf("organism-logAnalyticsDropdown", module)
  .addDecorator(withProvider)
  .add("log_analytics", () => (
    <BrowserRouter>
      <LogAnalyticsDropdown
        userInfo={{ role: 101 }}
        selectedDropDowns={{ univ: "Univ A" }}
        dropDownData={{
          user: [],
          univ: [],
          exam: [],
          quiz: [],
          exam_type: [],
          library: [],
        }}
      />
    </BrowserRouter>
  ));
