import React from "react";
import QuizPacks from "./index";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../redux/store";

const data = {
  header: [
    "ID",
    "Title(JP)",
    "Title(EN)",
    "Description (JP)",
    "Description (EN)",
    "Question format",
    "No. of Questions",
    "Public/Private",
  ],
  table_data: [{}],
};

storiesOf("pages-UserManagement", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("user_management", () => <QuizPacks data={data} />);
