import React from "react";
import UserRankUni from "./index";

export default {
  title: "molecules-UserRankUni",
};

const header = ["exam GroupA", "exam GroupB", "exam GroupC"];
const table_data = [
  {
    id: 1,
    user_name: "User1",
    user_point: "1",
  },
  {
    id: 2,
    user_name: "User2",
    user_point: "1",
  },
  {
    id: 3,
    user_name: "User3",
    user_point: "1",
  },
];

export const university_rank_uni = () => (
  <UserRankUni header={header} table_data={table_data} />
);
