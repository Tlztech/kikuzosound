import React from "react";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";

import store from "../../../../redux/store";
import PalpationLibraryAdd from "./index";


storiesOf("organisms-PalpationLibraryAdd", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("Palpation_Library_Modal", () => (
    <PalpationLibraryAdd isVisible="true" />
  ));
