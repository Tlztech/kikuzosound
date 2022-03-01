import React from "react";
import UcgLibraryPreview from "./index";

export default {
  title: "organisms-UcgLibraryPreview",
};

const previewItem = {
  video_file: "video/library_videos/1104.mov",
  title: "Title (JP) is required.",
  title_en: "Title (EN) is required.",
  normal_abnormal: "normal",
  ucg_explanation: "ucg explanation jp",
  ucg_explanation_en: "ucg explanation eng",
  public_private: "private",
};

export const Ucg_Library_Modal = () => (
  <UcgLibraryPreview isVisible="true" previewItem={previewItem} />
);
