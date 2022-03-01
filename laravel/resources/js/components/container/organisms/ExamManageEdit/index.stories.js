import React from "react";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";

import store from "../../../../redux/store";
import ExamManageEdit from "./index";

storiesOf("organisms-ExamManageEdit", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("Exam_Manage_Edit", () => <ExamManageEdit isVisible="true" id={1} />);
