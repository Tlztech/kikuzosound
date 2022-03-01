import React from "react";
import ExplanationButton from "./index";

export default {
  title: "molecules-ExplanationButton",
};

export const explanationButton = () => (
  <ExplanationButton
    title="No1"
    name="Explanation"
    isCorrect={true}
    onClick={() => alert("clicked")}
  />
);
