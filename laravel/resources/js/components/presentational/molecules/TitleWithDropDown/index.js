import React from "react";

//components
import Div from "../../atoms/Div";
import P from "../../atoms/P";

//i18n
import { withTranslation } from "react-i18next";

//icons
import ExpandMoreIcon from "@material-ui/icons/ExpandMore";

//style
import "./style.css";

//===================================================
// Component
//===================================================

class TitleWithDropDown extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      isDropDownVisible: false,
      selected: this.props.selected ? this.props.selected : "All",
    };
  }

  componentDidMount() {
    document.addEventListener("mousedown", (e) => handleClickOutside(e, this));
  }

  componentWillUnmount() {
    document.removeEventListener("mousedown", (e) =>
      handleClickOutside(e, this)
    );
  }

  render() {
    const { isDropDownVisible, selected } = this.state;
    const {
      dropDownList,
      onDropDownListClicked,
      title,
      t,
    } = this.props;
    return (
      <Div
        className="molecules-TitleWithDropDown-wrapper"
        setInputRef={(node) => (this.wrapperRef = node)}
      >
        <P>{title}</P>
        <Div
          onClick={() => {
            toggleDropDownBox(this);
          }}
          className="molecules-TitleBoxIcon-wrapper"
        >
          <P className="molecules-title">
            {this.props.selected || selected}
          </P>
          <ExpandMoreIcon
            className="molecules-expandmore"
            fontSize="medium"
            htmlColor="#828282"
          />
        </Div>
        {isDropDownVisible && (
          <Div
            className="molecules-dropdown-container"
            onClick={() => {
              toggleDropDownBox(this);
            }}
          >
            {dropDownList &&
              dropDownList.map((item, index) => {
                return (
                  <li
                    key={index}
                    className="molecules-title  molecules-dropdown-list"
                    onClick={() => {
                      handleClick(this, item);
                    }}
                  >
                    {item.value ? item.value : "-"}
                  </li>
                );
              })}
          </Div>
        )}
      </Div>
    );
  }
}

//===================================================
// Functions
//===================================================

/**
 * handle click
 * @param {*} context
 */
const handleClick = (context, item) => {
  context.setState({
    selected: item.value,
  });
  context.props.onDropDownListClicked(item.id);
};

/**
 * toogle drop down box
 * @param {*} context
 */
const toggleDropDownBox = (context) => {
  context.setState({
    isDropDownVisible: !context.state.isDropDownVisible,
  });
};

/**
 * handle click outside
 * @param {*} event
 * @param {*} context
 */
const handleClickOutside = (event, context) => {
  if (context.wrapperRef && !context.wrapperRef.contains(event.target)) {
    context.setState({
      isDropDownVisible: false,
    });
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
export default withTranslation("translation")(TitleWithDropDown);
