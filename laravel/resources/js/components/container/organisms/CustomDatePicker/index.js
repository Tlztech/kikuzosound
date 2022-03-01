import React from "react";

// libs
import { DateRange } from "react-date-range";

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
class CustomDatePicker extends React.Component {
  componentDidMount() {
    document.addEventListener("mousedown", (e) => handleClickOutside(e, this));
  }

  componentWillUnmount() {
    document.removeEventListener("mousedown", (e) =>
      handleClickOutside(e, this)
    );
  }

  render() {
    const {
      dateRange,
      handleRangeChanged,
      onPickerClicked,
      isDatePickerVisible,
      t,
    } = this.props;
    const { startDate, endDate } = dateRange;
    const startDateValue =
      startDate.getFullYear() +
      "/" +
      (startDate.getMonth() + 1) +
      "/" +
      startDate.getDate();
    const endDateValue =
      endDate.getFullYear() +
      "/" +
      (endDate.getMonth() + 1) +
      "/" +
      endDate.getDate();
    return (
      <Div
        className="organisms-customDatePicker-wrapper"
        setInputRef={(node) => (this.pickerWrapperRef = node)}
      >
        <Div
          className="organisms-picker-wrapper"
          onClick={() => onPickerClicked && onPickerClicked()}
        >
          <DateWithLabel title={t("start")} value={startDateValue} />
          <DateWithLabel title={t("end")} value={endDateValue} />
        </Div>
        {isDatePickerVisible && (
          <Div className="organisms-dropdown-container">
            <DateRange
              editableDateInputs={true}
              onChange={(item) => handleRangeChanged(item)}
              moveRangeOnFirstSelection={false}
              ranges={[dateRange]}
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
 * close datepicker on clicking outside
 * @param {*} e
 * @param {*} context
 */
const handleClickOutside = (e, context) => {
  if (
    context.pickerWrapperRef &&
    !context.pickerWrapperRef.contains(e.target)
  ) {
    context.props.handleClosePicker();
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
export default withTranslation("translation")(CustomDatePicker);
