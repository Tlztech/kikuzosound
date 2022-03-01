/**
 * before using connect in index.js
 */

// import React from "react";
// import LoginField from "./index";

// export default {
//   title: "molecules-LoginField",
// };

// export const login_field = () => <LoginField />;

/**
 * after using connect in index.js
 */
import React from "react";
import { Provider } from "react-redux";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { createStore } from "../../../../redux/store";
import LoginField from "./index";

const store = createStore();

const errors = {
  invalid_email: true,
  invalid_password: true
}

const withProvider = (story) => (
  <MemoryRouter initialEntries={["/"]}>
    <Provider store={store}>{story()}</Provider>
  </MemoryRouter>
);

storiesOf("molecules-LoginField", module)
  .addDecorator(withProvider)
  .add("login_field", () => <LoginField loginFieldErrors={errors}/>);
