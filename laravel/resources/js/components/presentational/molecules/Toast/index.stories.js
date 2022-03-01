import React from "react";
import { storiesOf } from "@storybook/react";

import Toast from "./index";

storiesOf("molecules-ToastComponent", module).add("success", () => {
  const message = { mode: "success", content: "Data added successfully" };
  return <Toast message={message} />;
});

storiesOf("molecules-ToastComponent", module).add("error", () => {
  const message = { mode: "error", content: "Failed to add data" };
  return <Toast message={message} />;
});

storiesOf("molecules-ToastComponent", module).add("warning", () => {
    const message = { mode: "warning", content: "User has to be Admin" };
    return <Toast message={message} />;
  });
  
  storiesOf("molecules-ToastComponent", module).add("info", () => {
    const message = { mode: "info", content: "User pending action" };
    return <Toast message={message} />;
  });
  