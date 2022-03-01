import React from "react";
import ExamTable from "./index";

export default {
  title: "molecules-ExamTable",
};
const data = {
  header: ["id", "exam_group", "exam", "user", "used_time","ok or miss"],
  table_data: [
    {
      id: "1",
      exam_group: "Group A",
      exam: "Exam 1",
      user: "User AA",
      used_time: "0:10",
    },
    {
      id: "2",
      exam_group: "Group B",
      exam: "Exam 2",
      user: "User BB",
      used_time: "0:12",
    },
  ],
};

export const exam_table = () => <ExamTable data={data} />;
