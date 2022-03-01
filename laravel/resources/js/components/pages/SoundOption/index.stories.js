import React from "react";
import SoundOptionPlayer from "./index";

const soundOptions = [
  { id: 1, title: "Sound 1", url: "http://abc.com/a.mp3" },
  { id: 2, title: "Sound 2", url: "http://abc.com/a.mp3" },
  { id: 3, title: "Sound 3", url: "http://abc.com/a.mp3" },
  { id: 4, title: "Sound 4", url: "http://abc.com/a.mp3" }
];

const timerValue = 30;

export const sound_option_player = () => (
  <SoundOptionPlayer
    quizTitle="Quiz Lungs Sound"
    title={"Lungs Sound"}
    soundOptions={soundOptions}
    timerValue={timerValue}
  />
);
