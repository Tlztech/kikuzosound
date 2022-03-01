import React from "react";
import XrayPreview from "./index";

export default {
  title: "organism-XrayPreviewModal",
};

const previewItem = {
  image_path: "/img/xray_images/1121.jpg",
  title: "Title (JP) is required.",
  title_en: "Title (EN) is required.",
  isNormal: 0,
  description: "description(jp)",
  description_en: "description(en)",
  isPublic: 1,
};

export const xray_preview = () => (
  <XrayPreview isVisible={true} previewItem={previewItem} />
);
