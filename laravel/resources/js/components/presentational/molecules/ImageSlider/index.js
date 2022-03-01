import React from "react";

// components
import Div from "../../atoms/Div";
import Image from "../../atoms/Image";

// Images
import NavigateBeforeIcon from "@material-ui/icons/NavigateBefore";
import NavigateNextIcon from "@material-ui/icons/NavigateNext";
import DefaultIcon from "../../../../assets/default_icon.jpg";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class ImageSlider extends React.Component {
  render() {
    const {
      url,
      totalImages,
      selectedIndex,
      onPreviousIconClicked,
      onNextIconClicked,
    } = this.props;
    return (
      <Div className="molecules-imageSlider-container">
        <Div className="molecules-image-wrapper">
          <NavigateBeforeIcon
            fontSize="large"
            onClick={onPreviousIconClicked && onPreviousIconClicked}
          />
          <Image url={url || DefaultIcon} />
          <NavigateNextIcon
            fontSize="large"
            onClick={onNextIconClicked && onNextIconClicked}
          />
        </Div>
        <Div className="molecules-circle-container">
          {[...Array(totalImages)].map((item, index) => (
            <Div
              key={index}
              className={
                index === selectedIndex
                  ? "molecules-selected-image-circle"
                  : "molecules-unselected-image-circle"
              }
            />
          ))}
        </Div>
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
export default ImageSlider;
