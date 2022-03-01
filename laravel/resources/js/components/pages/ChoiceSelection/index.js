import React from "react";

// components
import ChoiceSelectionTemplate from "../../container/templates/ChoiceSelection";

//css
import "./style.css";

//===================================================
// Component
//===================================================

class ChoiceSelection extends React.Component {
  render() {
    const { history } = this.props;
    return <ChoiceSelectionTemplate history={history} />;
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
export default ChoiceSelection;
