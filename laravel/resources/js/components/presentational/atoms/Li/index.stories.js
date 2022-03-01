import React from "react";
import Li from "./index";

export default {
  title: "atoms-Li",
};

const items = [{ name: "react" }, { name: "vue" }, { name: "angular" }];

export const list = () => <Li items={items} />;
