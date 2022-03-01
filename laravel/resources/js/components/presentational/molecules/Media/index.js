import React from "react";

// Styles
import "./style.css";

// Component
import Audio from "../../atoms/Audio/index";
import Video from "../../atoms/Video/index";

//===================================================
// Component
//===================================================

const Media = ({ file, type, height, width , hash }) => {
  switch (type) {
    case "sound":
      return (
        file && (
          <Audio
            className="mt-2 mb-2"
            src={
              typeof file == "object"
                ? URL.createObjectURL(file)
                : `${file}?${hash}`
            }
          />
        )
      );

    case "video":
      return (
        file && (
          <Video
            width={width}
            height={height}
            src={
              typeof file == "object"
                ? URL.createObjectURL(file)
                : `${file}?${hash}`
            }
          />
        )
      );
  }
};

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
export default React.memo(Media);
