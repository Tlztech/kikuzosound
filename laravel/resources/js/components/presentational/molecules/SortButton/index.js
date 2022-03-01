import React from "react";
import { withRouter } from "react-router-dom";

// components
import Div from "../../atoms/Div";
import Button from "../../atoms/Button";

//i18n
import { withTranslation } from "react-i18next";

// style
import "./style.css";

//===================================================
// Component
//===================================================
class SortButton extends React.Component {
  render() {
    const { onClick, isSort, t } = this.props;
    return (
      <Div className="molecules-SortButton-wrapper">
        <Button mode="active" onClick={onClick}>
          {isSort
            ? t("translation:unsort_lib_btn")
            : t("translation:sort_lib_btn")}
        </Button>
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
export default withTranslation("translation")(withRouter(SortButton));
