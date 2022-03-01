import React from "react";
import AusculaidePreview from "./index";

export default {
  title: "organism-AusculaidePreviewModal",
};

const previewItem = {
  title: "Title (JP) is required.",
  title_en: "Title (EN) is required.",
  soundtype: "lung_sound",
  image_path: "/img/library_images/1120.png",
  description: "description jp",
  description_en: "description en",
  normal_abnormal: "normal",
  explanatory_image: [
    {
      id: 1,
      image_path: "/img/stetho_sound_images/981.jpg",
    },
  ],
  explanatory_image_en: [
    {
      id: 1,
      image_path: "/img/stetho_sound_images/981.jpg",
    },
  ],
  public_private: "private",
  a_sound_file: "/audio/stetho_sounds/a_1122.mp3",
  pa_sound_file: "/audio/stetho_sounds/pa_1122.mp3",
  p_sound_file: "/audio/stetho_sounds/p_1122.mp3",
  pp_sound_file: "/audio/stetho_sounds/pp_1122.mp3",
  t_sound_file: "/audio/stetho_sounds/t_1122.mp3",
  pt_sound_file: "/audio/stetho_sounds/pt_1122.mp3",
  m_sound_file: "/audio/stetho_sounds/m_1122.mp3",
  pm_sound_file: "/audio/stetho_sounds/pm_1122.mp3",
};

export const ausculaide_preview = () => (
  <AusculaidePreview isVisible={true} previewItem={previewItem} />
);
