import React from "react";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../../redux/store";
import ResetPassword from "./index";

const errors = {
  invalid_email: true,
};

storiesOf("organisms-ResetPassword", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("Reset_password", () => <ResetPassword loginFieldErrors={errors} />);
