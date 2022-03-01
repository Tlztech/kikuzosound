import React from "react";
import TitleWithDropDown from "./index";

export default {
  title: "molecules-TitleWithDropDown",
};

const list = [
  {
    value: "All",
  },
  {
    value: "univ 1",
  },
  {
    value: "univ 2",
  },
];

export const titleWithDropDown = () => (
  <TitleWithDropDown
    dropDownSelection="All"
    dropDownList={list}
    onDropDownListClicked={(value) => alert(value)}
    title="UNIV"
  />
);
