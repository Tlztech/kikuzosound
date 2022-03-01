import React from "react";

// Components
import Div from "../../atoms/Div/index";
import Button from "../../atoms/Button/index";
import P from "../../atoms/P";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class ChoiceButtonWithIcon extends React.Component {
  render() {
    const { title, isSelected, isSubmited, onSelectClicked } = this.props;
    return (
      <Div className="molecules-ChoiceButtonWithIcon-wrapper">
        <Button
          mode="choice"
          onClick={onSelectClicked && onSelectClicked}
          className={
            isSelected
              ? "molecules-ChoiceButtonWithIcon-selected"
              : isSubmited
              ? "molecules-ChoiceButtonWithIcon-submited"
              : "molecules-ChoiceButtonWithIcon"
          }
        >
          <P className="molecules-ChoiceButtonWithIcon-title">
            {title}
            {isSubmited && "✔"}
          </P>
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
export default ChoiceButtonWithIcon;
