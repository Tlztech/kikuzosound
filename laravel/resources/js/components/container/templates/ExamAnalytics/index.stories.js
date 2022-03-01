import React from "react";
import { Provider } from "react-redux";
import { storiesOf } from "@storybook/react";
import { MemoryRouter, BrowserRouter } from "react-router-dom";
import { createStore } from "../../../../redux/store";
import ExamAnalytics from "./index";

const store = createStore();

const withProvider = (story) => (
  <MemoryRouter initialEntries={["/"]}>
    <Provider store={store}>{story()}</Provider>
  </MemoryRouter>
);

storiesOf("templates-ExamAnalytics", module)
  .addDecorator(withProvider)
  .add("exam_analytics", () => (
    <BrowserRouter>
      <ExamAnalytics />
    </BrowserRouter>
  ));
