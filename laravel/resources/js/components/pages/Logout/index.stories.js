import React from "react";
import Logout from "./index";
import { BrowserRouter, MemoryRouter } from "react-router-dom";
import { storiesOf } from "@storybook/react";
import { Provider } from "react-redux";
import store from "../../../redux/store";

const withProvider = (story) => (
  <Provider store={store}>
    <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
  </Provider>
);

storiesOf("pages-logout", module)
  .addDecorator(withProvider)
  .add("logout_page", () => (
    <BrowserRouter>
      <Logout/>
    </BrowserRouter>
  ));
