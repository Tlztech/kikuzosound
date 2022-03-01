import React from "react";
import AnswerWithExplain from "./index";

export default {
  title: "organisms-AnswerWithExplain",
};

const label = `Heart sounds at the base (aortic and pulmonary valve site) are S1 < S2 / S2 generated when the aortic and pulmonary valves close almost simultaneously.`;

export const Answer_Explain = () => (
  <AnswerWithExplain
    label={label}
    correctAnswer="This was the correct answer."
    myAnswer="This was your answer."
  />
);
