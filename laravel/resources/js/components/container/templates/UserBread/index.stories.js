import React from "react";
import UserBread from "./index";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../../redux/store";

const data = {
  header: ["ID", "UserName", "UserID", "MailAddress", "CreatedDate", "Action"],
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

storiesOf("templates-UserBread", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("user_bread", () => <UserBread data={data} />);
