import React from "react";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";

import Store from "../../../../redux/store";
import InspectionLibraryAdd from "./index";

storiesOf("organisms-InspectionLibraryAdd", module)
  .addDecorator((story) => (
    <Provider store={Store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("Inspection_Library_Modal", () => (
    <InspectionLibraryAdd isVisible="true" />
  ));
