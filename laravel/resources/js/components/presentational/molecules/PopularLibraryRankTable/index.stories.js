import React from "react";
import PopularLibraryRankTable from "./index";

export default {
  title: "molecules-PopuarResultUserRanking",
};

const table_data = {
  filtered: {
    rank_1: { name: "Sound 2", count: "50%" },
    rank_2: { name: "Sound 3", count: "60%" },
    rank_3: { name: "Sound 4", count: "70%" },
  },
  own_data: {
    rank_1: { name: "Ausculaide", count: "50%" },
    rank_2: { name: "Xray", count: "60%" },
    rank_3: { name: "Inspection", count: "70%" },
  },
  system_data: {
    rank_1: { name: "Ausculaide", count: "50%" },
    rank_2: { name: "Xray", count: "60%" },
    rank_3: { name: "Inspection", count: "70%" },
  },
};

export const popular_library_rank_table = () => (
  <PopularLibraryRankTable t={() => {}} tableData={table_data} />
);
