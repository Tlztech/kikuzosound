import React from "react";
import Home from "./index";
import { BrowserRouter, MemoryRouter } from "react-router-dom";
import { storiesOf } from "@storybook/react";
import { Provider } from "react-redux";
import store from "../../../redux/store";

storiesOf("pages-home", module)
  .addDecorator(story => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("home_page", () => (
    <BrowserRouter>
      <Home />
    </BrowserRouter>  
  ));
