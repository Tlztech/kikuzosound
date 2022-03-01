import React from "react";
import UcgLIbrary from "./index";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../redux/store";

const data = {
    header: [
        "ID",
        "Title",
        "An Illustration",
        "Library",
        "Content",
        "Answer Options",
        "Time Limit",
      ],
  table_data: [{}],
};

storiesOf("pages-UserManagement", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("user_management", () => <UcgLIbrary data={data} />);