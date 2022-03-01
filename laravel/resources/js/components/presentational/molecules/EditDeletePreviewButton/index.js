import React from "react";
import { connect } from "react-redux";

// components
import Div from "../../atoms/Div/index";
import Button from "../../atoms/Button/index";

//i18n
import { withTranslation } from "react-i18next";

// styles
import "./style.css";

//===================================================
// Component
//===================================================
class EditDeletePreviewButton extends React.Component {
  render() {
    const {
      onEditClicked,
      onDeleteClicked,
      onPreviewClicked,
      t,
      role,
      userInfo,
      disableEditDelete,
    } = this.props;
    return (
      <Div className="molecules-EditDeletePreviewButton-wrapper">
        {(role != 101 || userInfo.role == 101) && (
          <>
            <Button
              mode="preview"
              onClick={onPreviewClicked && onPreviewClicked}
            >
              {t("preview_btn")}
            </Button>
            <Button
              mode="active"
              class_style={disableEditDelete && "disabled-button"}
              disabled={disableEditDelete}
              onClick={onEditClicked && onEditClicked}
            >
              {t("edit_btn")}
            </Button>
            <Button
              mode="danger"
              disabled={disableEditDelete}
              class_style={disableEditDelete && "disabled-button"}
              onClick={onDeleteClicked && onDeleteClicked}
            >
              {t("delete_btn")}
            </Button>
          </>
        )}
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
const mapStateToProps = (state) => {
  return {
    userInfo: state.auth.userInfo && state.auth.userInfo.user,
  };
};

//===================================================
// Export
//===================================================
export default connect(
  mapStateToProps,
  {}
)(withTranslation("translation")(EditDeletePreviewButton));
