import React from "react";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../../redux/store";
import ForgetPassword from "./index";

export default {
  title: "templates-ForgetPassword",
};

const errors = {
  invalid_email: true,
};

storiesOf("templates-ForgotPassword", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("forgot_password", () => <ForgetPassword loginFieldErrors={errors} />);
