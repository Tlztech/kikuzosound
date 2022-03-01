import React from "react";
import ExamResultRankCorrectRateTable from "./index";

export default {
  title: "molecules-ExamResultRankCorrectRateTable",
};

const table_data = {
  filtered: {
    rank_1: { id: 1, name: "Sound 2", rate: "50%" },
    rank_2: { id: 2, name: "Sound 3", rate: "60%" },
    rank_3: { id: 3, name: "Sound 4", rate: "70%" },
  },
  own_data: {
    rank_1: { id: 1, name: "Ausculaide", rate: "50%" },
    rank_2: { id: 2, name: "Xray", rate: "60%" },
    rank_3: { id: 3, name: "Inspection", rate: "70%" },
  },
};

export const examresult_rank_correct_rate_table = () => (
  <ExamResultRankCorrectRateTable tableData={table_data} />
);
