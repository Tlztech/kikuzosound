import React from "react";
import TextareaWithLabel from "./index";

export default {
  title: "molecules-TextareaWithLabel"
};

export const TextArea_With_Label = () => (
  <TextareaWithLabel
    label="Enter a description: "
    placeholder="This is text area"
  ></TextareaWithLabel>
);
