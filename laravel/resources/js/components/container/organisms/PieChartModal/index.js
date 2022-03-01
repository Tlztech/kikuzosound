import React from "react";

// libs
import { Modal } from "react-bootstrap";

// Components
import Label from "../../../presentational/atoms/Label";
import Image from "../../../presentational/atoms/Image";
import Div from "../../../presentational/atoms/Div";
import PieChart from "../../../presentational/molecules/PieChart";

// Images
import CrossIcon from "../../../../assets/CrossIcon.png";

// css
import "./style.css";

//i18
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
class PieChartModal extends React.Component {
  componentDidMount() {
    this.props.handleFetchPieModalData();
  }

  render() {
    const {
      isVisible,
      onHidePieChartModal,
      pieChartData,
      t,
      isPieLoading,
    } = this.props;
    return (
      <Modal
        className="organism-pieChartModal-wrapper"
        show={isVisible}
        onHide={() => onHidePieChartModal && onHidePieChartModal()}
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
      >
        <Modal.Header>
          <Label>{t("study_time_by_library")}</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={() => onHidePieChartModal && onHidePieChartModal()}
          />
        </Modal.Header>
        <Modal.Body>
          {isPieLoading ? (
            <Div className="loading-indicator">Loading...</Div>
          ) : (
            <Div className="organism-piechart-list">
              {pieChartData &&
                pieChartData.map((item, index) => {
                  const modifiedLabels = item.data.labels.map((label) => {
                    return t(label);
                  });
                  return (
                    <PieChart
                      key={index}
                      data={{
                        datasets: item.data.datasets,
                        labels: modifiedLabels,
                      }}
                      title={item.title}
                    />
                  );
                })}
            </Div>
          )}
        </Modal.Body>
      </Modal>
    );
  }
}

//===================================================
// functions
//===================================================

//===================================================
// actions
//===================================================

//===================================================
// redux
//===================================================

//===================================================
// export
//===================================================
export default withTranslation("translation")(PieChartModal);
