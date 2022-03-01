import React from "react";
import InputPassword from "./index";
import { storiesOf } from "@storybook/react";
import { Provider } from "react-redux";
import { createStore } from "../../../../redux/store";
import { MemoryRouter } from "react-router-dom";

const store = createStore();

const withProvider = (story) => (
  <MemoryRouter initialEntries={["/"]}>
    <Provider store={store}>{story()}</Provider>
  </MemoryRouter>
);

storiesOf("molecules-InputPassword", module)
  .addDecorator(withProvider)
  .add("input_password", () => (
    <InputPassword label="Password" placeholder="Enter Password" />
  ));
