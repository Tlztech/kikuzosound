import React from "react";
import SimpleSoundOptionPlayer from "./index";

export default {
  title: "template-SimpleSoundOptionPlayer",
};

const soundOptions = [
  { id: 1, title: "Sound 1", url: "http://abc.com/a.mp3" },
  { id: 2, title: "Sound 2", url: "http://abc.com/a.mp3" },
  { id: 3, title: "Sound 3", url: "http://abc.com/a.mp3" },
  { id: 4, title: "Sound 4", url: "http://abc.com/a.mp3" },
];

const timerValue = 30;

export const simple_sound_option_player = () => (
  <SimpleSoundOptionPlayer
    quizTitle="Quiz Lungs Sound"
    title={"Lungs Sound"}
    soundOptions={soundOptions}
    timerValue={timerValue}
  />
);
