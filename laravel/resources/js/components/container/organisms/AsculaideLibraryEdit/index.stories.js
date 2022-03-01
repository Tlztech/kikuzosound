import React from "react";
import AsculaideLibraryEdit from "./index";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../../redux/store";

storiesOf("organisms-AsculaideLibraryEdit", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("ausculaide_Library_Modal", () => (
    <AsculaideLibraryEdit isVisible="true" />
  ));
