import React from "react";
import Label from "./index";

export default {
  title: "atoms-Label",
};

export const label = () => <Label>This is Label</Label>;
export const form = () => <Label mode="form">This is Label</Label>;
export const error = () => <Label mode="error">This is Label</Label>;
