import i18n from "i18next";
import Backend from "i18next-http-backend";
import LanguageDetector from "i18next-browser-languagedetector";
import { initReactI18next } from "react-i18next";

import translationJA from "../../public/locales/ja/translation.json";
import translationEN from "../../public/locales/en/translation.json";

import reset_passwordJA from "../../public/locales/ja/reset_password.json";
import reset_passwordEN from "../../public/locales/en/reset_password.json";

import palpation_libJA from "../../public/locales/ja/palpation_lib.json";
import palpation_libEN from "../../public/locales/en/palpation_lib.json";

import inspection_libJA from "../../public/locales/ja/inspection_lib.json";
import inspection_libEN from "../../public/locales/en/inspection_lib.json";

const resources = {
  ja: {
    reset_password: reset_passwordJA,
    palpation_lib: palpation_libJA,
    translation: translationJA,
    inspection_lib: inspection_libJA
  },
  en: {
    reset_password: reset_passwordEN,
    palpation_lib: palpation_libEN,
    translation: translationEN,
    inspection_lib: inspection_libEN
  }
};

const languageDetector = {
  init: Function.prototype,
  type: "languageDetector",
  async: true, // flags below detection to be async
  detect: async callback => {
    const savedLanguage = localStorage.getItem("selectedLanguage");
    const selectedLanguage = savedLanguage ? savedLanguage : "ja";
    if (!savedLanguage) {
      localStorage.setItem("selectedLanguage", selectedLanguage);
    }
    callback(selectedLanguage);
  },
  cacheUserLanguage: () => {}
};

i18n
  // load translation using http -> see /public/locales (i.e. https://github.com/i18next/react-i18next/tree/master/example/react/public/locales)
  // learn more: https://github.com/i18next/i18next-http-backend
  .use(Backend)
  // detect user language
  // learn more: https://github.com/i18next/i18next-browser-languageDetector
  .use(languageDetector)
  // pass the i18n instance to react-i18next.
  .use(initReactI18next)
  // init i18next
  // for all options read: https://www.i18next.com/overview/configuration-options
  .init({
    resources,
    fallbackLng: "en", // use en if detected lng is not available
    debug: true,

    saveMissing: true, // send not translated keys to endpoint
    keySeparator: false,
    interpolation: {
      escapeValue: false
    },
    react: {
      wait: true
    }
  });

export default i18n;
