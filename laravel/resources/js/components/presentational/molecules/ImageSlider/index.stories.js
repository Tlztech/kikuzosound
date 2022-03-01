import React from "react";
import ImageSlider from "./index";
import { storiesOf } from "@storybook/react";
import { Provider } from "react-redux";
import { createStore } from "../../../../redux/store";
import { MemoryRouter } from "react-router-dom";

const store = createStore();

storiesOf("molecules-ImageSlider", module)
  .addDecorator((story) => (
    <MemoryRouter initialEntries={["/"]}>
      <Provider store={store}>{story()}</Provider>
    </MemoryRouter>
  ))
  .add("image_slider", () => (
    <ImageSlider
      totalImages={2}
      selectedIndex={0}
      onNextIconClicked={() => console.log("prevoius")}
      onPreviousIconClicked={() => console.log("next")}
    />
  ));
