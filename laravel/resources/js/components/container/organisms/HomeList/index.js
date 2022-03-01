import React from "react";

// Components
import Div from "../../../presentational/atoms/Div";
import HomeListMolecule from "../../../presentational/molecules/HomeList";

//i18n
import { withTranslation } from "react-i18next";
import i18next from "i18next";

import { CircularProgress } from "@material-ui/core";

//css
import "./style.css";

//===================================================
// Component
//===================================================
class HomeList extends React.Component {
  render() {
    const { t, data } = this.props;
    const homeLists = data.HomeDataList;
    return (
      <>
        {homeLists && homeLists.length != 0 && !data.isLoading
          ? getTableData(this, homeLists)
          : getEmptyResult(this)}
      </>
    );
  }
}

//===================================================
// Functions
//===================================================
/**
 * Return maintenance list
 * @param {*} homeLists
 */
const getTableData = (context, homeLists) => {
  const selectedLanguage = i18next.language;
  return (
    <Div className="organisms-homelist-wrapper">
      {homeLists &&
        homeLists.map((item, index) => (
          <HomeListMolecule
            isLastItem={index === homeLists.length - 1}
            key={index}
            title={item.updated_at.split(" ")[0]}
            description={
              selectedLanguage == "ja" ? item.description : item.description_en
            }
          />
        ))}
    </Div>
  );
};

/**
 * display empty list
 * @param {*} context
 */
const getEmptyResult = (context) => {
  const { t } = context.props;
  return (
    <Div className="homelist-no-data">
      {context.props.data && context.props.data.isLoading ? (
        <CircularProgress />
      ) : (
        t("empty_data")
      )}
    </Div>
  );
};

//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================

//===================================================
// Export
//===================================================
export default withTranslation("translation")(HomeList);
