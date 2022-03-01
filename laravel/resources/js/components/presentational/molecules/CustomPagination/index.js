import React from "react";

// Libs
import ReactPaginate from "react-paginate";

// Components
import Div from "../../atoms/Div/index";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class CustomPagination extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      selectedIndex:0,
    };
  }

  render() {
    const { totalPage = 1 , currentPage=0} = this.props;
    const { selectedIndex } = this.state;
    return (
      <Div className="molecules-pagination-wrapper">
        <ReactPaginate
          previousLabel={"<<"}
          nextLabel={">>"}
          breakLabel={"..."}
          breakClassName={"break-me molecules-break-container"}
          pageCount={totalPage}
          marginPagesDisplayed={3}
          pageRangeDisplayed={4}
          onPageChange={(e) => handlePageChange(this, e)}
          containerClassName={"pagination"}
          pageClassName={"molecules-pagination-page"}
          activeClassName={"molecules-active-page"}
          previousClassName={
            selectedIndex === 0
              ? "previous molecules-previous-button"
              : "previous"
          }
          nextClassName={
            selectedIndex === totalPage - 1
              ? "next molecules-next-button"
              : "next"
          }
          forcePage={currentPage}
          // forcePage={
          //   totalPage < selectedIndex + 1 ? totalPage - 1 : selectedIndex
          // }
        />
      </Div>
    );
  }
}

//===================================================
// Functions
//===================================================
/**
 * get selected index
 * @param {*} context
 * @param {*} e
 */
const handlePageChange = (context, e) => {
  context.setState({ selectedIndex: e.selected }, () => {
    context.props.onPageChanged(context.state.selectedIndex);
  });
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
export default CustomPagination;
