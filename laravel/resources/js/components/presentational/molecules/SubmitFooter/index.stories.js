import React from "react";
import SubmitFooter from "./index";

export default {
  title: "molecules-SubmitFooter"
};

const data = [
    { id: 1, value: "what is your diagnosis" },
    { id: 2, value: "What is answer" },
    { id: 3, value: "Correct Time" }
  ];

export const Submit_Footer = () => (
  <SubmitFooter label="Observation" data={data}></SubmitFooter>
);
