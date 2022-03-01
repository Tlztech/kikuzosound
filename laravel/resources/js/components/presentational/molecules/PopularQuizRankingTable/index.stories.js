import React from "react";
import PopularQuizRankingTable from "./index";
import { storiesOf } from "@storybook/react";
import { MemoryRouter } from "react-router-dom";
import { Provider } from "react-redux";
import store from "../../../../redux/store";

const table_data = [
  {
    id: 1,
    filteredId: 1,
    filteredName: "Quiz 5",
    univId: 1,
    univName: "Quiz 5",
    systemId: 1,
    systemName: "Quiz 5",
  },
  {
    id: 2,
    filteredId: 2,
    filteredName: "Quiz 2",
    univId: 2,
    univName: "Quiz 2",
    systemId: 2,
    systemName: "Quiz 2",
  },
  {
    id: 3,
    filteredId: 3,
    filteredName: "Quiz 1",
    univId: 3,
    univName: "Quiz 1",
    systemId: 3,
    systemName: "Quiz 1",
  },
  {
    id: 4,
    filteredId: 4,
    filteredName: "Quiz 4",
    univId: 4,
    univName: "Quiz 4",
    systemId: 4,
    systemName: "Quiz 4",
  },
];

storiesOf("molecule-popularQuizRankingTable", module)
  .addDecorator((story) => (
    <Provider store={store}>
      <MemoryRouter initialEntries={["/"]}>{story()}</MemoryRouter>
    </Provider>
  ))
  .add("popular_quiz_ranking_table", () => (
    <PopularQuizRankingTable tableData={table_data} />
  ));
