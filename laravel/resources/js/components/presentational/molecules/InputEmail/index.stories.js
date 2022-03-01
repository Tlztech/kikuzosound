import React from "react";
import InputEmail from "./index";
import { storiesOf } from "@storybook/react";
import { Provider } from "react-redux";
import { createStore } from "../../../../redux/store";
import { MemoryRouter } from "react-router-dom";

const store = createStore();

storiesOf("molecules-InputEmail", module)
  .addDecorator((story) => (
    <MemoryRouter initialEntries={["/"]}>
      <Provider store={store}>{story()}</Provider>
    </MemoryRouter>
  ))
  .add("input_email", () => (
    <InputEmail label="New Email" placeholder="example@gmail.com" />
  ));
