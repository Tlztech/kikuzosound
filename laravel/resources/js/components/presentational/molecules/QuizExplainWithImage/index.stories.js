import React from "react";
import QuizExplainWithImage from "./index";
import PlayIcon from "../../../../assets/play.png";

export default {
  title: "molecules-quizExplainWithImage",
};

export const quiz_explain_with_image = () => (
  <QuizExplainWithImage
    answerExplanation="This is the explanation to the answer of the question above. Read this explanation carefully."
    explanationImageUrl={PlayIcon}
  />
);
