import React from "react";
import MultiplePreChoice from "./index";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../../redux/store";

const quizItem ={
  question_en: "Question EN?",
  question: "Question Ja?",
  case_age: 20,
  case_gender: 0,
  case: "Case jp",
  case_en: "Case en",
  is_optional: 0,
  stetho_sounds: [
    { id: 1, lib_type: 0 },
    { id: 2, lib_type: 1 },
    { id: 3, lib_type: 2 },
    { id: 4, lib_type: 3 },
    { id: 5, lib_type: 4 },
    { id: 6, lib_type: 5 },
    { id: 4, lib_type: 6 },
  ]
};

const withProvider = (story) => (
  <MemoryRouter initialEntries={["/"]}>
    <Provider store={store}>{story()}</Provider>
  </MemoryRouter>
);

storiesOf("organisms-MultiplePreChoice", module)
  .addDecorator(withProvider)
  .add("organism_multiple_prechoice", () => (
    <MultiplePreChoice
      quizTitle="Multiple Pre Choice"
      quiz_number={"2/2"}
      timeLimit={60}
      quizItem={quizItem}
    />
  ));
