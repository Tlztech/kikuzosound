import React from "react";
import ExamResultUserRankTable from "./index";

export default {
  title: "molecules-ExamResultUserRank",
};

const table_data = {
  filtered: {
    rank_1: { id: 1, name: "Test A" },
    rank_2: { id: 2, name: "Test b" },
  },
  own_data: {
    rank_1: { id: 1, name: "Test A" },
    rank_2: { id: 2, name: "Test b" },
  },
};

export const examresult_user_rank_table = () => (
  <ExamResultUserRankTable tableData={table_data} />
);
