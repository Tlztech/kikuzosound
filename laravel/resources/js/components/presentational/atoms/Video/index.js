import React from "react";

//css
import "./style.css";

//===================================================
// Component
//===================================================
class Video extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { src, width, height } = this.props;
    return (
      <>
        <video
          width={width}
          height={height}
          src={src}
          preload="true"
          controls
          controlsList="nodownload"
          onContextMenu={(e)=>e.preventDefault()}
        ></video>
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
export default Video;
