import React from "react";
import EcgPreview from "./index";

export default {
  title: "organism-EcgPreviewModal",
};

const previewItem = {
  sound_path: "/audio/stetho_sounds/1094.webm",
  title: "Title (JP) is required.",
  title_en: "Title (EN) is required.",
  soundtype: "lung_sound",
  image_path: "/img/library_images/1120.png",
  normal_abnormal: "normal",
  public_private: "private",
};

export const ecg_preview = () => (
  <EcgPreview isVisible={true} previewItem={previewItem} />
);
