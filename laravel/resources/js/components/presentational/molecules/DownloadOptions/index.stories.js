import React from "react";
import DownloadOptions from "./index";

export default {
  title: "molecules-DownloadOptions"
};

const data = {
  header: ["ID", "UserName", "UserID", "MailAddress", "CreatedDate", "Action"],
  table_data: [
    {
      ID: "1",
      UserName: "User Tao",
      UserID: "ID 23456",
      MailAddress: "example@gmail.com",
      CreatedDate: "220/07/19"
    },
    {
      ID: "2",
      UserName: "User Jiro",
      UserID: "ID 23456",
      MailAddress: "example@gmail.com",
      CreatedDate: "220/07/19"
    },
    {
      ID: "3",
      UserName: "User Sibero",
      UserID: "ID 23456",
      MailAddress: "example@gmail.com",
      CreatedDate: "220/07/19"
    }
  ]
};

export const Download_options = () => (
  <DownloadOptions data={data.table_data} />
);
