import React from "react";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class Checkbox extends React.Component {

  constructor(props) {
    super(props);
  }
  
  render() {
    return (
      <>
        <input 
          className="atoms-checkbox" 
          type="checkbox" 
          checked={this.props.checked} 
          id={this.props.id}
          onClick={this.props.onClick}
          onChange={this.props.onChange}
        /> &nbsp;
      </>
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
export default Checkbox;
