import React from "react";
import StethoPreview from "./index";

export default {
  title: "organisms-stethoPreview",
};

const previewItem = {
  sound_source: "/audio/stetho_sounds/1094.webm",
  title: "Title (JP) is required.",
  title_en: "Title (EN) is required.",
  normal_abnormal: "abnormal",
  ausculation_site: "Ausculation (JP) is required.",
  ausculation_site_en: "Ausculation (EN) is required",
  status: "Private",
  is_video: 1,
  soundtype: "lung_sound",
  description: "Description",
  image_list: [
    {
      created_at: "2021-02-01 18:45:54",
      disp_order: 0,
      id: 979,
      image_path: "/img/stetho_sound_images/979.jpg",
      lang: "ja",
      stetho_sound_id: 1118,
    },
    {
      created_at: "2021-02-01 18:45:54",
      disp_order: 0,
      id: 979,
      image_path: "/img/stetho_sound_images/979.jpg",
      lang: "ja",
      stetho_sound_id: 1118,
    },
    {
      created_at: "2021-02-01 18:45:54",
      disp_order: 0,
      id: 979,
      image_path: "/img/stetho_sound_images/979.jpg",
      lang: "ja",
      stetho_sound_id: 1118,
    },
  ],
  image_list_en: [
    {
      created_at: "2021-02-01 18:45:54",
      disp_order: 0,
      id: 979,
      image_path: "/img/stetho_sound_images/979.jpg",
      lang: "ja",
      stetho_sound_id: 1118,
    },
  ],
};

export const stetho_preview = () => (
  <StethoPreview isVisible={true} previewItem={previewItem} />
);
