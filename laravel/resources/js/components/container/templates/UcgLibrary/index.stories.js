import React from "react";
import UcgLIbrary from "./index";

export default {
  title: "templates-AsculaideLIbrary",
};
const data = {
  header: [
    "ID",
    "Title",
    "An Illustration",
    "Library",
    "Content",
    "Answer Options",
    "Time Limit",
  ],
  table_data: [{}],
};

export const user_bread = () => <UcgLIbrary data={data} />;
