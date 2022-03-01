import React from "react";
import Chart from "./index";

import { confidence as data } from "./data-vizualization";

export default {
  title: "molecules-Chart",
};

export const chart = () => <Chart chartData={data}/>;

