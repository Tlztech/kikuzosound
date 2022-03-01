import React from "react";
import MultipleDetails from "./index";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../../redux/store";

const quiz_options = [
  { id: 1, lib_type: "Stetho Sounds" },
  { id: 2, lib_type: "Ausculaide" },
  { id: 3, lib_type: "Plapation" },
  { id: 4, lib_type: "ECG" },
  { id: 5, lib_type: "Inspection" },
  { id: 6, lib_type: "X-Ray" },
  { id: 4, lib_type: "UCG" },
];

const dropdown_items = [
  { id: 1, value: "What's your diagnosis?" },
  { id: 2, value: "What's your diagnosis?" },
  { id: 3, value: "What's your diagnosis?" },
];

const withProvider = (story) => (
  <MemoryRouter initialEntries={["/"]}>
    <Provider store={store}>{story()}</Provider>
  </MemoryRouter>
);

storiesOf("organisms-MultipleDetails", module)
  .addDecorator(withProvider)
  .add("organism_multiple_details", () => (
    <MultipleDetails
      quizTitle="Ausculaide Sound Test"
      quiz_number={"11/1"}
      quizItem={{ stetho_sounds: quiz_options }}
      timeLimit={20}
      submittedMultipleAnswers={dropdown_items}
      t={() => {}}
    />
  ));
