import React from "react";
import QuizMultipleExplanation from "./index";

export default {
  title: "organism-QuizMultipleExplanation",
};

const soundOptions = [
  { id: 1, title: "Sound 1", url: "http://abc.com/a.mp3" },
  { id: 2, title: "Sound 2", url: "http://abc.com/a.mp3", isSelected: true },
  { id: 3, title: "Sound 3", url: "http://abc.com/a.mp3" },
];

const multipleQuizzes = {
  id: 1,
  title: "Palpation",
  quizOption: {
    id: 1,
    lungsSoundUrl: "http://abc.com/a.mp3",
    videoUrl: "http://abc.com/a.mp4",
  },
  soundOptions: soundOptions,
  description:
    "This is the description to lungs sound above. This is the description to lungs sound above. This is the description to lungs sound above. ",
};

export const quiz_multiple_explanation = () => (
  <QuizMultipleExplanation multipleQuizzes={multipleQuizzes} />
);
