import React from "react";
import DragDropList from "./index";

export default {
  title: "molecules-DragDropList",
};

export const dragDrop_list = () => (
  <DragDropList title="Exam Test" onClick={() => console.log("clicked")} />
);
