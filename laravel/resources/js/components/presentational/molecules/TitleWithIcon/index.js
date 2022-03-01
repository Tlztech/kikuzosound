import React from "react";

//components
import Div from "../../atoms/Div/";
import Label from "../../atoms/Label";
import DownloadOptions from "../../molecules/DownloadOptions";

//style
import "./style.css";

//===================================================
// Component
//===================================================
class TitleWithIcon extends React.Component {
  render() {
    const {
      label,
      csvData,
      csvMode,
      isDownloading,
      setDownloadCsv,
      handleFetchCsvData,
    } = this.props;
    return (
      <Div className="molecules-TitleWithIconContainer">
        <Label>{label}</Label>
        <DownloadOptions
          data={csvData}
          csv_mode={csvMode || "rankingCsv"}
          filename={label}
          isDownloading={isDownloading}
          setDownloadCsv={(isDownload) => setDownloadCsv(isDownload)}
          handleFetchCsvData={() => handleFetchCsvData()}
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
export default TitleWithIcon;
