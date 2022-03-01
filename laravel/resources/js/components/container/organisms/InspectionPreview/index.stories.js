import React from "react";
import InspectionPreview from "./index";

export default {
  title: "organisms-inspectionPreview",
};

const previewItem = {
  sound_path: "/audio/stetho_sounds/1094.webm",
  video_path: "/video/library_videos/1094.mov",
  title: "Title (JP) is required.",
  title_en: "Title (EN) is required.",
  is_normal: "Abnormal",
  description: "Description (JP) is required.",
  description_en: "Description (EN) is required",
  status: "Private",
};

export const inspection_preview = () => (
  <InspectionPreview isVisible={true} previewItem={previewItem} />
);
