import React from "react";
import ChoiceSelection from "./index";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../../redux/store";

const quiz_options = [
  { id: 1, title: "Stetho Sounds" },
  { id: 2, title: "Ausculaide" },
  { id: 3, title: "Plapation" },
  { id: 4, title: "ECG" },
  { id: 5, title: "Inspection" },
  { id: 6, title: "X-Ray" },
];

const withProvider = (story) => (
  <MemoryRouter initialEntries={["/"]}>
    <Provider store={store}>{story()}</Provider>
  </MemoryRouter>
);

storiesOf("templates-ChoiceSelection", module)
  .addDecorator(withProvider)
  .add("choice_selection_simple", () => <ChoiceSelection mode="simple" />)
  .add("choice_selection_multiple", () => <ChoiceSelection mode="multiple" />);
