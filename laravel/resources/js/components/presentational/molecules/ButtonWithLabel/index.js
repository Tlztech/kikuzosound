import React from "react";

import Div from "../../atoms/Div/index";
import Button from "../../atoms/Button/index";
import Label from "../../atoms/Label/index";
// css
import "./style.css";

//===================================================
// Component
//===================================================
class ButtonWithLabel extends React.Component {
  constructor(props) {
    super(props);
  }

  componentDidMount() {}

  render() {
    const { onClicked } = this.props;
    return (
      <Div className="molecules-ButtonWithLabel-wrapper">
        <Button mode="library" onClick={onClicked}>
          Stetho Sound
        </Button>
        <Label>{this.props.label}</Label>
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
export default ButtonWithLabel;
