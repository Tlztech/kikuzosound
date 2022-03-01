import React from "react";
import StethoLibraryAdd from "./index";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../../redux/store";


storiesOf("organisms-StethoLibraryAdd", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("Stetho_Library_Modal", () => <StethoLibraryAdd isVisible="true" />);

