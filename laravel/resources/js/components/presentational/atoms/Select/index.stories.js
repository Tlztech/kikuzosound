import React from "react";
import Select from "./index";

export default {
  title: "atoms-Select",
};

const items = [
  { id: 1, value: "a" },
  { id: 2, value: "b" },
  { id: 3, value: "c" },
];

export const select = () => <Select items={items}></Select>;
