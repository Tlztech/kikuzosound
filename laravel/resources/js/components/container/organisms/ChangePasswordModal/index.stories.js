import React from "react";
import i18next from "i18next";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";

import "../../../../i18n.js";
import ChangePasswordModal from "./index";
import store from "../../../../redux/store";

i18next.changeLanguage("en");

storiesOf("organism-changePasswordModal", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("Change Password Modal", () => <ChangePasswordModal isVisible={true} />);
