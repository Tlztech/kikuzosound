import React from "react";
import LineChart from "./index";

export default {
  title: "organism-LineChart",
};

const datasets = [
  {
    label: "Average",
    data: [
      { x: "05:22", y: 50 },
      { x: "12:13", y: 29 },
      { x: "13:45", y: 43 },
      { x: "18:31", y: 60 },
      { x: "19:05", y: 58 },
      { x: "14:55", y: 53 },
      { x: "08:33", y: 63 },
      { x: "01:00", y: 43 },
    ],
    backgroundColor: "rgba(155, 89, 132, 1)",
    borderColor: "rgba(155,89,132,1)",
    pointRadius: 4,
    pointHoverRadius: 8,
  },
  {
    label: "Univ A",
    data: [
      { x: "05:22", y: 48 },
      { x: "12:13", y: 59 },
      { x: "13:45", y: 33 },
      { x: "18:31", y: 50 },
      { x: "19:05", y: 78 },
      { x: "14:55", y: 93 },
      { x: "08:33", y: 23 },
      { x: "01:00", y: 73 },
    ],
    backgroundColor: "rgba(255, 99, 132, 1)",
    borderColor: "rgba(255,99,132,1)",
    pointRadius: 4,
    pointHoverRadius: 8,
  },
  {
    label: "Univ B",
    data: [
      { x: "05:22", y: 54 },
      { x: "12:13", y: 0 },
      { x: "13:45", y: 63 },
      { x: "18:31", y: 70 },
      { x: "19:05", y: 89 },
      { x: "14:23", y: 9 },
      { x: "07:33", y: 43 },
      { x: "02:00", y: 13 },
    ],
    backgroundColor: "rgba(205, 132, 202, 1)",
    borderColor: "rgba(205, 132, 202, 1)",
    pointRadius: 4,
    pointHoverRadius: 8,
  },
];

export const organism_linechart_user = () => (
  <LineChart datasets={datasets} chartType="user" />
);

export const organism_linechart_univ = () => (
  <LineChart datasets={datasets} chartType="univ" />
);
