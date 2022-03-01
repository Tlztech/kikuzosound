import React from "react";
import BR from "./index";

export default {
  title: "atoms-BR",
};

export const Break = () => {
  return (
    <p>
      Line will break here.
      <BR />
      New line.
    </p>
  );
};
