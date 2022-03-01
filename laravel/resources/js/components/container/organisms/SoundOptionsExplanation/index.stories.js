import React from "react";
import SoundOptionExplanation from "./index";

export default {
  title: "organism-SoundOptionExplanation",
};

const soundOptions = [
  { id: 1, title: "Sound 1", url: "http://abc.com/a.mp3" },
  { id: 2, title: "Sound 2", url: "http://abc.com/a.mp3", isSelected: true },
  { id: 3, title: "Sound 3", url: "http://abc.com/a.mp3" },
  { id: 4, title: "Sound 4", url: "http://abc.com/a.mp3" },
];

export const sound_option_explanation = () => (
  <SoundOptionExplanation title={"Lungs Sound"} soundOptions={soundOptions} />
);
