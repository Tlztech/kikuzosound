import React from "react";
import UserManagement from "./index";
import { config, withXD } from "storybook-addon-xd-designs";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../redux/store";

const artboardUrl =
  "https://xd.adobe.com/view/eacb78d0-07f8-489f-81f4-34ca4a998528-bf98/screen/1c4a8e22-5ef4-471b-b760-41ce99b110a3/Desktop";

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

storiesOf("pages-UserManagement", module)
  .addDecorator(withXD)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("user_management", () => <UserManagement data={data} />, {
    design: config({
      artboardUrl,
    }),
  });
