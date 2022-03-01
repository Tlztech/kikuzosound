import React from "react";

// bootstrap
import { Table as TableBB } from "react-bootstrap";

// style
import "./style.css";

//===================================================
// Component
//===================================================
class Table extends React.Component {
  render() {
    const { size, className, children, striped, bordered } = this.props;
    return (
      <TableBB
        responsive
        size={size}
        className={className}
        striped={striped}
        bordered={bordered}
      >
        {children}
      </TableBB>
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
export default Table;
