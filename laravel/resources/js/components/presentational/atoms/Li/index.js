import React from "react";

//css
import "./style.css";

//===================================================
// Component
//===================================================

class Li extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      items: this.props.items ? this.props.items : [],
      class_name: "",
      mode_class: "",
    };
  }

  componentDidMount() {
    switchMode(this);
  }

  render() {
    const { class_name, mode_class, items } = this.state;
    return (
      <ol className={"atoms-ol" + " " + mode_class}>
        {items.map((value, index) => {
          return (
            <li
              className={"atoms-li" + class_name + " "}
              key={index}
              value={value.id}
            >
              {value.name}
            </li>
          );
        })}
      </ol>
    );
  }
}

//===================================================
// Functions
//===================================================

/**
 * switch button modes
 */
const switchMode = (context) => {
  context.setState({
    class_name: context.props.className ? context.props.className : "",
  });
  switch (context.props.mode) {
    case "number":
      context.setState({ mode_class: "atoms-ol-number" });
      break;
    case "alphabet":
      context.setState({ mode_class: "atoms-ol-alphabet" });
      break;
    case "none":
      context.setState({ mode_class: "atoms-ol-none" });
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
export default Li;
