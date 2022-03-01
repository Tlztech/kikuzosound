import React from "react";
import QuizPreview from "./index";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../../redux/store";

const withProvider = (story) => (
  <MemoryRouter initialEntries={["/"]}>
    <Provider store={store}>{story()}</Provider>
  </MemoryRouter>
);

storiesOf("templates-QuizPreview", module)
  .addDecorator(withProvider)
  .add("quiz_preview", () => <QuizPreview />);
