import React from "react";

//libs
import { Form } from "react-bootstrap";

// css
import "./style.css";

// i18next
import { withTranslation } from "react-i18next";
import i18next from "i18next";

//===================================================
// Component
//===================================================
class Select extends React.Component {
  constructor(props) {
    super(props);
    const { className, tagName } = this.props;
    this.state = {
      mode_class: "",
      className: className,
      name: tagName,
    };
  }

  componentDidMount() {
    setMode(this);
  }

  render() {
    const { className, mode_class } = this.state;
    const { t, items, onChange, tagName, value, dropdownType } = this.props;
    let options = [];
    const title =
      value &&
      items.find((item) => item.id == parseInt(value)) &&
      items.find((item) => item.id == parseInt(value)).value;

    options = items.map((list, index) => {
      const listTitle = list
        ? i18next.language === "en"
          ? list.value_en
            ? list.value_en
            : list.value
          : list.value
        : "";
      const listItem = listTitle
        ? listTitle.length > 40
          ? listTitle.slice(0, 40) + "..."
          : listTitle
        : "";
      // const listItem =
      //   list && list.value.length > 40
      //     ? list.value.slice(0, 40) + "..."
      //     : list.value;
      return (
        <option key={index} value={list.id}>
          {i18next.exists(listItem)? t(listItem) : listItem}
        </option>
      );
    });
    return (
      <Form.Control
        as="select"
        className={"atoms-Select" + " " + className + " " + mode_class}
        name={tagName}
        onChange={onChange}
        value={value}
        title={title || ""}
        id={"selectDropdown"}
      >
        {options}
      </Form.Control>
    );
  }
}

//===================================================
// Functions
//===================================================

/**
 *
 * @param {*} context
 */
const setMode = (context) => {
  const { mode } = context.props;
  switch (mode) {
    case "small":
      this.setState({ mode_class: "atoms-Select-small" });
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
export default withTranslation("translation")(Select);
