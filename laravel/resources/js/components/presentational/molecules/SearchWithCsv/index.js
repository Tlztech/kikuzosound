import React from "react";
import Div from "../../atoms/Div";
import SearchInput from "../SearchInput";
import DownloadOptions from "../DownloadOptions";

//css
import "./style.css";

//css

class SearchWithCsv extends React.Component {
  constructor(props) {
    super(props);
  }
  render() {
    const { onChange, data, csv_mode, filename } = this.props;
    return (
      <Div className="top">
        {/* <Button
          mode="success"
          onClick={handleAddModalVisible}
        >
          {t("add_new")}
        </Button> */}
        <Div className="SearchWithDownloadOption">
          <SearchInput onChange={(event) => onChange(event)} />
          <DownloadOptions
            data={data}
            csv_mode={csv_mode}
            filename={filename}
          />
        </Div>
      </Div>
    );
  }
}

export default SearchWithCsv;
