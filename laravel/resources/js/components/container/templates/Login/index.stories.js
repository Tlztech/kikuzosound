import React from "react";
import { Provider } from "react-redux";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { createStore } from "../../../../redux/store";
import LoginTemplate from "./index";

const store = createStore();
const errors = {
  invalid_email: true,
  invalid_password: true,
};

const withProvider = (story) => (
  <MemoryRouter initialEntries={["/"]}>
    <Provider store={store}>{story()}</Provider>
  </MemoryRouter>
);

storiesOf("templates-Login", module)
  .addDecorator(withProvider)
  .add("template-Login", () => <LoginTemplate loginFieldErrors={errors} />);
