import React from "react";
import QuizPacks from "./index";

export default {
  title: "templates-QuizPacks",
};
const data = {
  header: [
    "ID",
    "Title",
    "Auscultation sound type",
    "Auscultation site",
    "Normal abnormal",
    "status",
    "Supervisor",
    "Public / Private",
    "Update date and time",
  ],
  table_data: [{}],
};

export const user_bread = () => <QuizPacks data={data} />;
