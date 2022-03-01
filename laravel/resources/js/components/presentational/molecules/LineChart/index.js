import React from "react";

//libs
import { Scatter } from "react-chartjs-2";

// Components
import Div from "../../atoms/Div";

// styles
import "./style.css";

//i18next
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
class LineChart extends React.Component {
  render() {
    const { datasets, no_legend, isChartLoading } = this.props;
    const label = datasets && datasets.map(item => item.label);
    // console.log(datasets);
    return (
      <Div className="molecules-LineChart-wrapper">
        <Scatter
          data={
            !isChartLoading
              ? {
                  datasets: datasets,
                  labels: label
                }
              : {}
          }
          height={480}
          options={{
            maintainAspectRatio: false,
            tooltips: {
              callbacks: {
                label: function (tooltipItem, data) {
                  let allTitle = data && data.datasets.map(item => item.tittle);
                  let label = data.labels[tooltipItem.datasetIndex];
                  let title = allTitle[tooltipItem.datasetIndex];
                  return (
                    label +
                    ",  " +
                    title +
                    ",  " +
                    tooltipItem.xLabel +
                    ",  " +
                    tooltipItem.yLabel
                  );
                }
              }
            },
            scales: {
              yAxes: [
                {
                  ticks: {
                    beginAtZero: true,
                    padding: 12,
                    min: 0,
                    max: 100,
                    callback: function (value) {
                      return value % 20 === 0 ? value + "%" : "";
                    }
                  },
                  gridLines: {
                    drawBorder: false,
                    drawOnChartArea: true
                  }
                }
              ],
              xAxes: [
                {
                  type: "time",
                  time: {
                    parser: "HH:mm:ss",
                    unit: "hour",
                    stepSize: 1,
                    displayFormats: {
                      hour: "HH:mm:ss"
                    },
                    tooltipFormat: "HH:mm:ss"
                  },
                  ticks: {
                    min: "00:00:00",
                    max: "06:00:00",
                    callback: (value, index) =>
                      index === 12 ? "06:00:00" : value
                  },
                  gridLines: {
                    drawBorder: false,
                    drawOnChartArea: true
                  }
                }
              ]
            }
          }}
          legend={{
            display: no_legend ? false : true,
            align: "end",
            position: "top",
            labels: {
              usePointStyle: true,
              padding: 24,
              boxWidth: 6
            }
          }}
        />
      </Div>
    );
  }
}

//===================================================
// Functions
//===================================================

//===================================================
// Actions
//===================================================
/**
 * Render only when chart data changes
 * @param {*} prevProps
 * @param {*} nextProps
 * @returns
 */
const checkChange = (prevProps, nextProps) => {
  if (prevProps.isChartLoading === nextProps.isChartLoading) {
    return true;
  }
};

//===================================================
// Redux
//===================================================

//===================================================
// Export
//===================================================
export default withTranslation("translation")(
  React.memo(LineChart, checkChange)
);
