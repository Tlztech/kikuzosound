import React from "react";
import PalpationPreview from "./index";

export default {
  title: "organisms-palpationPreview",
};

const previewItem = {
  sound_file: "/audio/stetho_sounds/1085.mp3",
  public_private: "private",
  title: "audio and video save",
  title_en: "audio and video save",
  soundtype: "unspecified",
  area: "-",
  normal_abnormal: "abnormal",
  video_file: "/video/library_videos/1101.mov",
};

export const palpation_preview = () => (
  <PalpationPreview isVisible={true} previewItem={previewItem} />
);
