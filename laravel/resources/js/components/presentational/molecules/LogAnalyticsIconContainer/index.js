import React from "react";

//components
import Div from "../../atoms/Div";
import Image from "../../atoms/Image";
import DownloadOptions from "../../molecules/DownloadOptions";

//images
import PieChart from "../../../../assets/pie-chart.png";
import BarChart from "../../../../assets/bar.png";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class LogAnalyticsIconContainer extends React.Component {
  render() {
    const {
      handleOpenRankingModal,
      handleOpenPieChartModal,
      csvData,
      isDownloading,
      setDownloadCsv,
      handleFetchCsvData,
    } = this.props;
    return (
      <Div className="molecules-LogAnalyticsIconContainer">
        <Image url={BarChart} onClick={() => handleOpenRankingModal()} />
        <Image url={PieChart} onClick={() => handleOpenPieChartModal()} />
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
export default LogAnalyticsIconContainer;
