import React from "react";

// components
import Div from "../../atoms/Div";
import Button from "../../atoms/Button";

// styles
import "./style.css";

//i18
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
class EditDeleteButton extends React.Component {
  render() {
    const { onEditClicked, onDeleteClicked, t } = this.props;
    return (
      <Div className="molecules-EditDeleteButton-wrapper">
        <Button mode="active" onClick={onEditClicked}>
          {t("edit_btn")}
        </Button>
        <Button mode="danger" onClick={onDeleteClicked}>
          {t("delete_btn")}
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
export default withTranslation("translation")(EditDeleteButton);
