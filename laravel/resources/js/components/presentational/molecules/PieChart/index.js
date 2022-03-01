import React from "react";

//libs
import { Doughnut } from "react-chartjs-2";

// Components
import Div from "../../atoms/Div";

// styles
import "./style.css";

//i18next
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
class PieChart extends React.Component {
  render() {
    const { data, title, t } = this.props;
    return (
      <Div className="molecules-PieChart-wrapper">
        <Doughnut
          data={data}
          height={380}
          options={{
            maintainAspectRatio: false,
            tooltips: {
              callbacks: {
                label: function (tooltipItem, data) {
                  const label = data.labels[tooltipItem.index];
                  const dataValue = data.datasets[0].data;
                  const value = dataValue[tooltipItem.index];
                  return `${label}: ${value}%`;
                },
              },
            },
            title: {
              text: t(title),
              display: true,
              fontColor: "#000000",
              fontSize: 14,
            },
            legend: {
              display: true,
              position: "top",
              labels: {
                boxWidth: 30,
                padding: 16,
                fontSize: 12,
              },
            },
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

//===================================================
// Redux
//===================================================

//===================================================
// Export
//===================================================
export default withTranslation("translation")(PieChart);
