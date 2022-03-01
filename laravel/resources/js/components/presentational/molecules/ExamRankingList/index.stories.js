import React from "react";
import { Provider } from "react-redux";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { createStore } from "../../../../redux/store";
import ExamRankingList from "./index";

const store = createStore();

const withProvider = (story) => (
  <MemoryRouter initialEntries={["/"]}>
    <Provider store={store}>{story()}</Provider>
  </MemoryRouter>
);

storiesOf("molecules-ExamRankingList", module)
  .addDecorator(withProvider)
  .add("ExamRankingList", () => <ExamRankingList />);
