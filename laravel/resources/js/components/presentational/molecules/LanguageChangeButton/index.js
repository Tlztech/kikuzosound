import React from "react";

// components
import Div from "../../atoms/Div/index";
import Select from "../../atoms/Select";

// icons
import LanguageIcon from "@material-ui/icons/Language";

// style
import "./style.css";

// i18next
import i18next from "i18next";

const availableLanguages = [
  { id: 1, value: "JP", code: "ja" },
  { id: 2, value: "EN", code: "en" },
];

//===================================================
// Component
//===================================================
class LanguageChangeButton extends React.Component {
  constructor(props) {
    super(props);
    const initialLang = availableLanguages.find(
      (item) => item.code === i18next.language
    );
    this.state = {
      selectedLanguage: initialLang ? initialLang.id : availableLanguages[0].id,
    };
  }

  render() {
    const { selectedLanguage } = this.state;
    let selectedLang = selectedLanguage == 1 ? "japaneseText" : "englishText";
    return (
      <Div className="molecules-languageChangeButton-wrapper">
        <LanguageIcon fontSize="medium" className="langIcon" />
        <Div className={selectedLang}>
          <Select
            value={selectedLanguage}
            items={availableLanguages}
            onChange={(event) => handleChangeLanguage(event, this)}
          />
        </Div>
      </Div>
    );
  }
}

//===================================================
// Functions
//===================================================
/**
 * get selected language
 * @param {*} event
 * @param {*} context
 */
const handleChangeLanguage = (event, context) => {
  const selectedId = event.target.value;
  context.setState({ selectedLanguage: selectedId });
  const obtainedLanguage = availableLanguages.find(
    (item) => item.id === parseInt(selectedId)
  );
  if (obtainedLanguage) {
    i18next.changeLanguage(obtainedLanguage.code);
    localStorage.setItem("selectedLanguage", obtainedLanguage.code);
  }
};

//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================

//===================================================
// Export
//===================================================
export default LanguageChangeButton;
