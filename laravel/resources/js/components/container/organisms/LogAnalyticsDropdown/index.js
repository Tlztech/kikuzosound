import React from "react";

//components
import Div from "../../../presentational/atoms/Div";
import TitleWithDropDown from "../../../presentational/molecules/TitleWithDropDown";

//css
import "./style.css";

//i18
import { withTranslation } from "react-i18next";

// const dropDownItems = ["user", "exam_type", "exam", "quiz", "library"];

//===================================================
// Component
//===================================================
class LogAnalyticsDropdown extends React.Component {
  render() {
    const {
      t,
      dropDownData,
      userInfo,
      handleSelectedDropdownItem,
      selectedDropDowns,
    } = this.props;
    return (
      <Div className="organism-logAnalyticsDropdown-wrapper">
        {userInfo.role === 101 && (
          <TitleWithDropDown
            dropDownSelection={selectedDropDowns.univ || "All"}
            dropDownList={[{ value: "All", id: null }, ...dropDownData.univ]}
            onDropDownListClicked={(value) =>
              handleSelectedDropdownItem(value, "univ")
            }
            title={t("group")}
          />
        )}
        {/* {dropDownItems.map((dropDownKey, index) => {
          const dropDownList = [
            { value: "All", id: null },
            ...dropDownData[dropDownKey],
          ];
          return (
            <TitleWithDropDown
              key={index}
              dropDownSelection={
                selectedDropDowns[dropDownKey] || dropDownList[0].value
              }
              dropDownList={dropDownList}
              onDropDownListClicked={(value) =>
                handleSelectedDropdownItem(value, dropDownKey)
              }
              title={t(dropDownKey)}
            />
          );
        })} */}
          <TitleWithDropDown
            dropDownSelection={selectedDropDowns.user || "All"}
            dropDownList={[{ value: "All", id: null }, ...dropDownData.user]}
            onDropDownListClicked={(value) =>
              handleSelectedDropdownItem(value, "user")
            }
            title={t("user")}
          />
        <TitleWithDropDown
          dropDownSelection={selectedDropDowns.exam_type || "All"}
          dropDownList={[{ value: "All", id: null }, ...dropDownData.exam_type]}
          onDropDownListClicked={(value) =>
            handleSelectedDropdownItem(value, "exam_type")
          }
          title={t("exam_type")}
        />
          <TitleWithDropDown
            dropDownSelection={selectedDropDowns.exam || "All"}
            dropDownList={[{ value: "All", id: null }, ...dropDownData.exam]}
            onDropDownListClicked={(value) =>
              handleSelectedDropdownItem(value, "exam")
            }
            title={t("exam")}
          />
          <TitleWithDropDown
            dropDownSelection={selectedDropDowns.exam || "All"}
            dropDownList={[{ value: "All", id: null }, ...dropDownData.quiz]}
            onDropDownListClicked={(value) =>
              handleSelectedDropdownItem(value, "quiz")
            }
            title={t("quiz")}
          />
          <TitleWithDropDown
            dropDownSelection={selectedDropDowns.exam || "All"}
            dropDownList={[{ value: "All", id: null }, ...dropDownData.library]}
            onDropDownListClicked={(value) =>
              handleSelectedDropdownItem(value, "library")
            }
            title={t("library")}
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
export default withTranslation("translation")(LogAnalyticsDropdown);
