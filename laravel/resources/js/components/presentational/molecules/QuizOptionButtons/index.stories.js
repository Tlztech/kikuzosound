import React from "react";
import QuizOptionButtons from "./index";

export default {
  title: "molecules-QuizOptionButtons",
};

const soundOptions = [
  { id: 1, title: "Sound 1", url: "http://abc.com/a.mp3" },
  { id: 2, title: "Sound 2", url: "http://abc.com/a.mp3" },
  { id: 3, title: "Sound 3", url: "http://abc.com/a.mp3" },
  { id: 4, title: "Sound 4", url: "http://abc.com/a.mp3" },
];

export const quiz_options_buttons = () => (
  <QuizOptionButtons soundOptions={soundOptions} />
);
