import React from "react";
import Button from "./index";

export default {
  title: "atoms-Button"
};

export const add_button = () => <Button mode="active">Active</Button>;

export const success_button = () => <Button mode="success">Success</Button>;

export const delete_button = () => <Button mode="danger">Delete</Button>;

export const cancel_button = () => <Button mode="cancel">cancel</Button>;
