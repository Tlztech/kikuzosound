import React from "react";

// components
import Div from "../../atoms/Div/index";
import Label from "../../atoms/Label/index";
import Audio from "../../atoms/Audio/index";

// style
import "./style.css";

//===================================================
// Component
//===================================================
class SoundWithLabel extends React.Component {
  render() {
    const { file, label } = this.props;
    return (
      <Div className="audio-label-molecules">
        <Audio
          className="testb"
          src={
            typeof file == "object"
              ? URL.createObjectURL(file)
              : `${file}`
          }
        />
        <Label>{label}</Label>
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
export default SoundWithLabel;
