import React from "react";
import InspectionLibraryEdit from "./index";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../../redux/store";

storiesOf("organisms-InspectionLibraryEdit", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("Inspection_Library_Edit_Modal", () => (
    <InspectionLibraryEdit isVisible="true" />
  ));
