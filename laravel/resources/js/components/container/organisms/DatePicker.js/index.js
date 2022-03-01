import React from "react";
import ReactDOM from "react-dom";

// libs
import { Calendar } from "react-date-range";

// Components
import Div from "../../../presentational/atoms/Div";
import DateWithLabel from "../../../presentational/molecules/DateWithLabel";

//i18n
import { withTranslation } from "react-i18next";

// css
import "./style.css";
import "react-date-range/dist/styles.css";
import "react-date-range/dist/theme/default.css";

//===================================================
// Component
//===================================================
class DatePicker extends React.Component {
  constructor(props) {
    super(props);
    this.onClickOnPage = this.onClickOnPage.bind(this);
  }

  componentDidMount() {
    document.body.addEventListener("mousedown", this.onClickOnPage, true);
  }

  componentWillUnmount() {
    document.body.removeEventListener("mousedown", this.onClickOnPage, true);
  }

  onClickOnPage(e) {
    const { datePickerdomNode } = this.props;
    let domNode = datePickerdomNode && ReactDOM.findDOMNode(datePickerdomNode);
    // console.log(domNode);
    if (domNode && !domNode.contains(e.target)) {
      this.props.handleOutsidePickerClick();
    }
  }

  render() {
    const {
      title,
      t,
      isPickerVisible,
      onDatePickerClicked,
      initialDate,
      handleDateChanged,
      lastDate
    } = this.props;
    const minDate = new Date(2012, 0, 1);
    return (
      <Div className="organisms-datePicker-wrapper">
        <Div
          className="organisms-picker-wrapper"
          onClick={() => onDatePickerClicked && onDatePickerClicked()}
        >
          <DateWithLabel title={t(title)} value={getDateValues(initialDate)} />
        </Div>
        {isPickerVisible && (
          <Div className="organisms-dropdown-container">
            <Calendar
              date={initialDate}
              onChange={item => handleDateChanged(item)}
              maxDate={new Date()}
              minDate={title === "end" ? lastDate : minDate}
            />
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
 * Get date in YYYY/MM/DD format
 * @param {*} item
 * @returns
 */
const getDateValues = item => {
  var month = item.getMonth() + 1;
  if (item.getMonth() < 9) month = "0" + (item.getMonth() + 1);
  const result = item.getFullYear() + "/" + month + "/" + item.getDate();
  return result;
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
export default withTranslation("translation")(DatePicker);
