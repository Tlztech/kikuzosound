import React from "react";
import { Provider } from "react-redux";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import store from "../../../../redux/store";
import ResetPasswordFromMailForm from "./index";

const withProvider = (story) => (
  <MemoryRouter initialEntries={["/"]}>
    <Provider store={store}>{story()}</Provider>
  </MemoryRouter>
);

storiesOf("organisms-ResetPasswordFromMailForm", module)
  .addDecorator(withProvider)
  .add("ResetPasswordFromMailForm", () => (
    <ResetPasswordFromMailForm />
  ));
