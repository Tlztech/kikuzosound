import React from "react";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";

import EcgLibraryEdit from "./index";
import store from "../../../../redux/store";

storiesOf("organisms-EcgLibraryEdit", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("Ecg Library Edit Modal", () => <EcgLibraryEdit isVisible="true" />);
