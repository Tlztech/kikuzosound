import React from "react";
import DragDrop from "./index";

export default {
  title: "molecule-DragDrop",
};

const tasks = [
  {
    id: "1",
    taskName: "Quiz 1",
    type: "examLists",
  },
  {
    id: "2",
    taskName: "Quiz 2",
    type: "examLists",
  },
  {
    id: "3",
    taskName: "Quiz 3",
    type: "examSteps",
  },
  {
    id: "4",
    taskName: "Quiz 4",
    type: "examSteps",
  },
  {
    id: "5",
    taskName: "Quiz 5",
    type: "examSteps",
  },
];

export const dragDrop = () => <DragDrop tasks={tasks} />;
