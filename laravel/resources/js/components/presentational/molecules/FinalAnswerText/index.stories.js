import React from "react";
import FinalAnswerText from "./index";

export default {
  title: "molecules-FinalAnswerText",
};

export const finalAnswerText = () => (
  <FinalAnswerText
    correctAnswer="This was the correct answer."
    myAnswer="This was your answer."
  />
);
