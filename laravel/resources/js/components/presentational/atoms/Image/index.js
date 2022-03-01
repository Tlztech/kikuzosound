import React from "react";

//css
import "./style.css";

//===================================================
// Component
//===================================================
class Image extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      mode_class: "",
      error_class: "",
    };
  }

  componentDidMount() {
    switchMode(this);
  }

  componentDidUpdate(prevProps) {
    if (this.props.isPossibleError != prevProps.isPossibleError) {
      this.setState({
        error_class: "",
      });
    }
  }

  render() {
    const { mode_class, error_class } = this.state;
    const { url, className, onClick, isPossibleError } = this.props;
    return (
      <img
        draggable={false}
        src={url}
        className={
          "atoms-Image" + " " + className + " " + mode_class + " " + error_class
        }
        onClick={onClick}
        onError={(e) => {
          e.target.src = `/img/no_image.png`;
          isPossibleError
            ? this.setState({
                error_class: "error",
              })
            : this.setState({
                error_class: "",
              });
        }}
      />
    );
  }
}

//===================================================
// Functions
//===================================================
const switchMode = (context) => {
  const { mode } = context.props;
  switch (mode) {
    case "":
    case "upload":
      context.setState({ mode_class: "atoms-Image-upload" });
      break;
    case "search":
      context.setState({ mode_class: "atoms-Image-search" });
      break;
    case "drag":
      context.setState({ mode_class: "atoms-Image-drag" });
      break;
    case "selected-input":
      context.setState({ mode_class: "atoms-Image-selected-input" });
      break;
    default:
      break;
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
export default Image;
