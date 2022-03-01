import React from "react";
import UserBoxLayout from "./index";

export default {
  title: "molecules-UserBoxLayout",
};

const header = ["user", "user", "user"];
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

const items = [
  { id: 1, name: "施設内コンテンツ人気ランキング" },
  { id: 2, name: "全体コンテンツ人気ランキング" },
  { id: 3, name: "施設内EXAM成績ランキング" },
  { id: 4, name: "施設内EXAM問題正答率" },
  { id: 5, name: "施設内サイトない学習時間ランキング" },
];

export const user_box_layout = () => (
  <UserBoxLayout header={header} table_data={table_data} items={items}/>
);
