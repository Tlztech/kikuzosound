import React from "react";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";

import store from "../../../../redux/store";
import PalpationLibraryEdit from "./index";

storiesOf("organisms-PalpationLibraryEdit", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("Palpation_Library_Modal", () => (
    <PalpationLibraryEdit isVisible="true" />
  ));
