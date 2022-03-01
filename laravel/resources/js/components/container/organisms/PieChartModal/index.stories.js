import React from "react";
import PieChartModal from "./index";

export default {
  title: "organism-pieChartModal",
};

const data_set = [
  {
    title: "filtered",
    data: {
      labels: [
        "Ausculaide",
        "Stetho",
        "Palpation",
        "ECG",
        "Inspection",
        "X-ray",
      ],
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
    },
  },
  {
    title: "all_of_own_data",
    data: {
      labels: [
        "Ausculaide",
        "Stetho",
        "Palpation",
        "ECG",
        "Inspection",
        "X-ray",
      ],
      datasets: [
        {
          data: [33, 14, 20, 13, 50, 37],
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
    },
  },
  {
    title: "all_of_system_data",
    data: {
      labels: [
        "Ausculaide",
        "Stetho",
        "Palpation",
        "ECG",
        "Inspection",
        "X-ray",
      ],
      datasets: [
        {
          data: [234, 204, 52, 63, 85, 97],
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
    },
  },
];

export const pie_chart_modal = () => (
  <PieChartModal isVisible={true} pieChartData={data_set} />
);
