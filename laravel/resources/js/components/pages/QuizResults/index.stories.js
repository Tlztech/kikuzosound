import React from "react";
import QuizResult from "./index";

import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../redux/store";

const explanationResult = [
  {
    id: 1,
    title: "No1",
    name: "Explanation",
    isCorrect: false,
  },
  {
    id: 2,
    title: "No2",
    name: "Explanation",
    isCorrect: true,
  },
  {
    id: 3,
    title: "No3",
    name: "Explanation",
    isCorrect: true,
  },
];

const withProvider = (story) => (
  <MemoryRouter initialEntries={["/"]}>
    <Provider store={store}>{story()}</Provider>
  </MemoryRouter>
);

storiesOf("pages-QuizResults", module)
  .addDecorator(withProvider)
  .add("pages_quiz_results", () => <QuizResult data={explanationResult} />);
