import React from "react";

//libs

//css
import "./style.css";

//===================================================
// Component
//===================================================
class Audio extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { src, className } = this.props;
    return (
      <>
        <audio
          className={"atoms-audio" + " " + className}
          src={src}
          preload="auto"
          controls
          controlsList="nodownload"
          onContextMenu={(e)=>e.preventDefault()}
        ></audio>
      </>
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
export default Audio;
