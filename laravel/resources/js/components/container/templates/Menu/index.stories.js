import React from "react";
import Menu from "./index";
import { storiesOf } from "@storybook/react";
import { BrowserRouter, MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../../redux/store";

storiesOf("templates-menu", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("Menu", () => (
    <BrowserRouter>
      <Menu />
    </BrowserRouter>
  ));
