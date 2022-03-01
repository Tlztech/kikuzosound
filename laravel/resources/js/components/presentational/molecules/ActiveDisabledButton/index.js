import React from "react";

// Components
import Div from "../../atoms/Div/index";
import Button from "../../atoms/Button/index";

//i18n
import { withTranslation } from "react-i18next";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class ActiveDisabledButton extends React.Component {
  render() {
    const { onActiveClicked, onDisabledClicked, t, isActive } = this.props;
    return (
      <Div className="molecules-activeDisabledButton-wrapper">
        <Button
          mode="cancel"
          class_style={
            isActive
              ? "molecules-active-button molecules-selected-button"
              : "molecules-active-button"
          }
          onClick={onActiveClicked && onActiveClicked}
        >
          {t("active_button")}
        </Button>
        <Button
          mode="cancel"
          class_style={
            !isActive
              ? "molecules-disabled-button molecules-selected-button"
              : "molecules-disabled-button"
          }
          onClick={onDisabledClicked && onDisabledClicked}
        >
          {t("disabled")}
        </Button>
      </Div>
    );
  }
}

//===================================================
// Functions
//===================================================

//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================

//===================================================
// Export
//===================================================
export default withTranslation("translation")(ActiveDisabledButton);
