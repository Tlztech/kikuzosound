import React from "react";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";

import store from "../../../../redux/store";
import QuizResult from "./index";

storiesOf("templates-QuizResults", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("templates_quiz_results", () => <QuizResult />);
