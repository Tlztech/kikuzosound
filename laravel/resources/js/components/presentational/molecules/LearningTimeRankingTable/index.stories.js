import React from "react";
import LearningTimeRankingTable from "./index";

export default {
  title: "molecules-LearningTimeRanking",
};

const table_data = {
  filtered: {
    rank_1: { id: 1, name: "Sound 2" },
    rank_2: { id: 2, name: "Sound 3" },
    rank_3: { id: 3, name: "Sound 4" },
  },
  own: {
    rank_1: { id: 1, name: "Ausculaide" },
    rank_2: { id: 2, name: "Xray" },
    rank_3: { id: 3, name: "Inspection" },
  },
};

export const learning_time_rank_table = () => (
  <LearningTimeRankingTable tableData={table_data} />
);
