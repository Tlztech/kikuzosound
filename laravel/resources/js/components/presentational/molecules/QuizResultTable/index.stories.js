import React from "react";
import QuizResultTable from "./index";

export default {
  title: "molecules-QuizResultTable",
};

const table_data = {
  filtered: {
    rank_1: { id: 1382, name: "Sound 2", rate: "50%" },
    rank_2: { id: 1383, name: "Sound 3", rate: "60%" },
    rank_3: { id: 1384, name: "Sound 4", rate: "70%" },
    rank_4: { id: 1385, name: "Sound 5", rate: "80%" },
    rank_5: { id: 1386, name: "Sound 6", rate: "90%" },
  },
  own_data: {
    rank_1: { id: 1143, name: "Tracheal sounds", rate: "50%" },
    rank_2: { id: 1144, name: "Heart sounds", rate: "60%" },
    rank_3: { id: 1145, name: "lungs sounds", rate: "70%" },
    rank_4: { id: 1146, name: "Tracheal sounds", rate: "80%" },
    rank_5: { id: 1147, name: "Heart sounds", rate: "90%" },
  },
};

export const quiz_result_table = () => (
  <QuizResultTable tableData={table_data} />
);
