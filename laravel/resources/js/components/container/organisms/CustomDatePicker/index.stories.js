import React from "react";
import CustomDatePicker from "./index";

export default {
  title: "organisms-CustomDatePicker",
};

export const custom_date_picker = () => (
  <CustomDatePicker
    dateRange={{
      startDate: new Date(),
      endDate: new Date(),
      key: "selection",
    }}
    isDatePickerVisible={true}
  />
);
