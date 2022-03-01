import React from "react";

//components
import Div from "../../../presentational/atoms/Div";
import Button from "../../../presentational/atoms/Button";
import SearchInput from "../../../presentational/molecules/SearchInput";
import DownloadOptions from "../../../presentational/molecules/DownloadOptions";

//css
import "./style.css";

//i18n
import { withTranslation } from "react-i18next";

class AddSearchWithCsv extends React.Component {
  render() {
    const {
      t,
      onChange,
      onClick,
      data,
      csv_mode,
      filename,
      onAddNewClicked,
      handleFetchCsvData,
      isDownloading,
      setDownloadCsv,
      disabled,
      search_input_ref
    } = this.props;
    return (
      <Div className="organism-AddSearchWithCsv-wrapper">
        <Button mode="success" onClick={onAddNewClicked} disabled={disabled}>
          {t("add_new")}
        </Button>
        <Div className="organism-SearchWithDownloadOption">
          <SearchInput onChange={(event) => onChange(event)} onClick={(event) => onClick(event)} search_input_ref={search_input_ref}/>
          <DownloadOptions
            handleFetchCsvData={() => handleFetchCsvData()}
            data={data}
            csv_mode={csv_mode}
            filename={filename}
            isDownloading={isDownloading}
            setDownloadCsv={(isDownload) => setDownloadCsv(isDownload)}
          />
        </Div>
      </Div>
    );
  }
}

export default withTranslation("translation")(AddSearchWithCsv);
