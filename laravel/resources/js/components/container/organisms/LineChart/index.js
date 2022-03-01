import React from "react";

// Components
import Div from "../../../presentational/atoms/Div";
import Linechart from "../../../presentational/molecules/LineChart";

// styles
import "./style.css";

//i18next
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
class LineChart extends React.Component {
  render() {
    const { datasets, no_legend, ...rest } = this.props;
    return (
      <Div className="organism-LineChart-wrapper">
        <Linechart datasets={datasets} no_legend={no_legend} {...rest} />
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
export default withTranslation("translation")(LineChart);
