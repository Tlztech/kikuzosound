import React from "react";
import Home from "./index";
import { BrowserRouter, MemoryRouter } from "react-router-dom";
import { storiesOf } from "@storybook/react";
import { Provider } from "react-redux";
import store from "../../../../redux/store";

const homeLists = [
  {
    id: 1,
    title: "2021/01/12",
    description:
      "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed doeiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim adminim veniam, quis nostrud exercitation ullamco laboris nisi utaliquip ex ea commodo consequat.",
  },
  {
    id: 2,
    title: "2021/01/11",
    description:
      "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed doeiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim adminim veniam, quis nostrud exercitation ullamco laboris nisi utaliquip ex ea commodo consequat.",
  },
  {
    id: 3,
    title: "2021/01/10",
    description:
      "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed doeiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim adminim veniam, quis nostrud exercitation ullamco laboris nisi utaliquip ex ea commodo consequat.",
  },
  {
    id: 4,
    title: "2021/01/09",
    description:
      "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed doeiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim adminim veniam, quis nostrud exercitation ullamco laboris nisi utaliquip ex ea commodo consequat.",
  },
  {
    id: 5,
    title: "2021/01/08",
    description:
      "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed doeiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim adminim veniam, quis nostrud exercitation ullamco laboris nisi utaliquip ex ea commodo consequat.",
  },
];

storiesOf("templates-home", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("home", () => (
    <BrowserRouter>
      <Home homeLists={homeLists} />
    </BrowserRouter>
  ));
