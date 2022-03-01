import React from "react";
import ChoiceButtonWithIcon from "./index";

export default {
  title: "molecules-ChoiceButtonWithIcon",
};

export const defaultChoiceButton = () => (
  <ChoiceButtonWithIcon
    title="Stetho Sounds"
    isSelected={false}
    isSubmited={false}
    onSelectClicked={() => alert("defaultChoiceButton")}
  />
);

export const selectChoiceButton = () => (
  <ChoiceButtonWithIcon
    title="Stetho Sounds"
    isSelected={true}
    isSubmited={false}
    onSelectClicked={() => alert("selectChoiceButton")}
  />
);

export const submitedChoiceButtonWithIcon = () => (
  <ChoiceButtonWithIcon
    title="Stetho Sounds"
    isSelected={false}
    isSubmited={true}
    onSelectClicked={() => alert("submitedChoiceButtonWithIcon")}
  />
);
