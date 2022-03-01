import React from "react";
import LogoutModal from "./index";
import { BrowserRouter, MemoryRouter } from "react-router-dom";
import { storiesOf } from "@storybook/react";
import { Provider } from "react-redux";
import store from "../../../../redux/store";

const withProvider = (story) => (
  <Provider store={store}>
    <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
  </Provider>
);

storiesOf("organism-LogoutModal", module)
  .addDecorator(withProvider)
  .add("logoutModal", () => (
    <BrowserRouter>
      <LogoutModal isVisible={true} />
    </BrowserRouter>
  ));
