import React from "react";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";

import store from "../../../redux/store";
import ResetPasswordFromMail from "./index";

storiesOf("pages-ResetPasswordFromMail", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("reset_password_from_mail", () => <ResetPasswordFromMail />);
