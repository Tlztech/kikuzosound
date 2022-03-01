import React from "react";
import ResultWithExplanationButton from "./index";

export default {
  title: "organism-ResultWithExplanationButton",
};

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

export const resultWithExplanationButton = () => (
  <ResultWithExplanationButton
    explanationResult={explanationResult}
    answer="2/3 The right answer"
    onExplanationClicked={() => alert("Explanation clicked")}
    onFinishClicked={() => alert("Finish clicked")}
  />
);
