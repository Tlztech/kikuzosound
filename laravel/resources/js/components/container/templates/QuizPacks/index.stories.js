import React from "react";
import QuizPacks from "./index";

export default {
  title: "templates-QuizPacks",
};
const data = {
  header: [
    "ID",
    "Title(JP)",
    "Title(EN)",
    "Description (JP)",
    "Description (EN)",
    "Question format",
    "No. of Questions",
    "Public/Private",
  ],
  table_data: [{}],
};

export const user_bread = () => <QuizPacks data={data} />;
