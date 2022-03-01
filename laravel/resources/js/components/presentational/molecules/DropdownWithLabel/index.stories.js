import React from "react";
import DropdownLabel from "./index";

export default {
  title: "molecules-DropdownLabel",
};

const dropdown_items = [
  { id: 1, value: "What's your diagnosis?" },
  { id: 2, value: "What's your diagnosis?" },
  { id: 3, value: "What's your diagnosis?" },
];

export const dropdown_label = () => (
  <DropdownLabel dropdown_label="Diagnosis" dropdown_items={dropdown_items} />
);
