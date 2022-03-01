import React from "react";
import QuizOptionTextBox from "./index";

export default {
  title: "molecules-QuizOptionTextBox",
};

const soundOptions = [
  { id: 1, title: "Sound 1", url: "http://abc.com/a.mp3" },
  { id: 2, title: "Sound 2", url: "http://abc.com/a.mp3" },
  { id: 3, title: "Sound 3", url: "http://abc.com/a.mp3", is_correct: true },
];

export const quiz_options_textbox = () => (
  <QuizOptionTextBox soundOptions={soundOptions} />
);
