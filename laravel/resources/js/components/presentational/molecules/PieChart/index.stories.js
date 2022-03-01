import React from "react";
import PieChart from "./index";

export default {
  title: "molecules-PieChart",
};

const data_set = {
  labels: ["Ausculaide", "Stetho", "Palpation", "ECG", "Inspection", "X-ray"],
  datasets: [
    {
      data: [23, 24, 2, 3, 5, 7],
      backgroundColor: [
        "#4285f4",
        "#ea4335",
        "#fbbc04",
        "#34a853",
        "#ff6d01",
        "#46bdc6",
      ],
      hoverBackgroundColor: [
        "#4285f4",
        "#ea4335",
        "#fbbc04",
        "#34a853",
        "#ff6d01",
        "#46bdc6",
      ],
    },
  ],
};

export const filtered_piechart = () => (
  <PieChart title="filtered" data={data_set} />
);

export const all_of_own_data = () => (
  <PieChart title="all_of_own_data" data={data_set} />
);

export const all_of_system_data = () => (
  <PieChart title="all_of_system_data" data={data_set} />
);
