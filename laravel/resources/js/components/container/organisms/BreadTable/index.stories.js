import React from "react";
import BreadTable from "./index";

export default {
  title: "organisms-BreadTable",
};

const header = [
  "ID",
  "UserName",
  "UserID",
  "MailAddress",
  "CreatedDate",
  "Action",
];

const data = {
  table_data: [
    {
      ID: "1",
      UserName: "User Tao",
      UserID: "ID 23456",
      MailAddress: "example@gmail.com",
      CreatedDate: "220/07/19",
    },
    {
      ID: "2",
      UserName: "User Jiro",
      UserID: "ID 23456",
      MailAddress: "example@gmail.com",
      CreatedDate: "220/07/19",
    },
    {
      ID: "3",
      UserName: "User Sibero",
      UserID: "ID 23456",
      MailAddress: "example@gmail.com",
      CreatedDate: "220/07/19",
    },
  ],
};

export const user_table = () => <BreadTable header={header} data={data} />;
