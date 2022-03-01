import React from "react";

import { DragDropContext, Draggable, Droppable } from "react-beautiful-dnd";
// bootstrap
import EditModal from "../QuizPackEdit/index";
import DeleteModal from "../DeleteModal/index";
import EditDeletePreviewButton from "../../../presentational/molecules/EditDeletePreviewButton/index";

import { DragNdropIcon } from "../../../../assets";
import Image from "../../../presentational/atoms/Image";

// style
import "./style.css";

//===================================================
// Component
//===================================================
class QuicPackBread extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      deleteItem: "",
      editItem: [],
      isEditModalVisible: false,
      isDeleteModalVisible: false,
    };
  }

  render() {
    const table_data =
      this.props.data && this.props.data.table_data
        ? this.props.data.table_data
        : [];
    const { isEditModalVisible, isDeleteModalVisible, editItem } = this.state;
    return (
      <>
        {table_data.length != 0 && !this.props.data.isLoading
          ? getTableData(this, table_data)
          : getEmptyResult(this)}
        <EditModal
          isVisible={isEditModalVisible}
          onHideEditModal={() => handleEditModalVisible(this, false, editItem)}
          editItem={editItem}
          updateQuizPack={(data, index) =>
            this.props.updateQuizPack(data, index)
          }
          exam_groups={this.props.exam_groups}
          quiz_list={this.props.quiz_list}
        />
        <DeleteModal
          isVisible={isDeleteModalVisible}
          onHideDeleteModal={() => handleDeleteModalVisible(this, false)}
          onDeletePressed={() => handleDeleteItem(this, this.state.deleteItem)}
        />
      </>
    );
  }
}

//===================================================
// Functions
//===================================================
/**
 * Get sorted list after drag ends
 * @param {*} result
 * @param {*} table_data
 * @param {*} context
 */
 const onDragEnd = (result, table_data, context) => {
  const { destination, source } = result;
  if (!destination) {
    return;
  }
  if (
    destination.droppableId === source.droppableId &&
    destination.index === source.index
  ) {
    return;
  }
  const items = Array.from(table_data);
  const [reorderedItem] = items.splice(source.index, 1);
  items.splice(destination.index, 0, reorderedItem);
  context.props.updateOrder(items, context.props.userInfo.id);
};

/**
 * show table data
 * @param {*} context
 * @param {*} table_data
 */
const getTableData = (context, table_data) => {
  const { userInfo } = context.props;
  return (
    <DragDropContext onDragEnd={(e) => onDragEnd(e, table_data, context)}>
      <Droppable droppableId="exam-drag">
        {(provided) => (
          <tbody {...provided.droppableProps} ref={provided.innerRef}>
            {table_data.map((value, index) => {
              return (
                <Draggable
                  key={value.ID}
                  draggableId={value.ID && value.ID.toString()}
                  index={index}
                  isDragDisabled={false}
                >
                  {(provided) => (
                    <tr
                      {...provided.draggableProps}
                      ref={provided.innerRef}
                      key={value.disp_order}
                    >
                      <td
                        {...provided.dragHandleProps}
                        style={{ width: "30px" }}
                        className="text-center"
                      >
                        <Image mode="drag" url={DragNdropIcon} />
                      </td>
                      <td style={{ width: "50px" }}>{value.ID}</td>
                      <td>{value.title}</td>
                      <td>{value.title_en}</td>
                      <td>{value.description}</td>
                      <td>{value.description_en}</td>
                      <td>{value.question_format}</td>
                      <td>{value.number_of_questions}</td>
                      <td>{value.release}</td>
                      <td colSpan="2">
                      {!value.author ||
                        (value.author && value.author.role == userInfo.role) ||
                        userInfo.role == 101 ? (
                          value.user_id == userInfo.id ? (
                            <EditDeletePreviewButton
                              onEditClicked={() =>
                                handleEditModalVisible(context, true, value)
                              }
                              onDeleteClicked={() =>
                                handleDeleteModalVisible(
                                  context,
                                  true,
                                  value,
                                  index
                                )
                              }
                              onPreviewClicked={() =>
                                handlePreviewModalVisible(context, value)
                              }
                            />
                          ) : (
                            <EditDeletePreviewButton
                              onPreviewClicked={() =>
                                handlePreviewModalVisible(context, value)
                              }
                              disableEditDelete={true}
                            />
                          )
                        ) : (
                          ""
                        )}
                      </td>
                    </tr>
                  )}
                </Draggable>
              );
            })}
          </tbody>
        )}
      </Droppable>
    </DragDropContext>
  );
};

/**
 * show empty table
 * @param {*} context
 */
const getEmptyResult = (context) => {
  return (
    <tbody>
      {context.props.data && context.props.data.isLoading ? (
        <tr>
          <td className="no-data">Loading... </td>
        </tr>
      ) : (
        <tr>
          <td className="no-data">No data Found. </td>
        </tr>
      )}
    </tbody>
  );
};

/**
 * show/hide edit modal
 * @param {*} context
 * @param {*} isVisible
 */
const handleEditModalVisible = (context, isVisible, value, test) => {
  if (test != "cancel")
    context.setState({ isEditModalVisible: isVisible, editItem: value });
  else {
    console.log(context.props.data);
  }
};

/**
 * show/hide delete modal
 * @param {*} context
 * @param {*} isVisible
 * @param {*} index
 */
const handleDeleteModalVisible = (context, isVisible, index) => {
  context.setState({ isDeleteModalVisible: isVisible, deleteItem: index });
};

/**
 * send delete item props
 * @param {*} context
 */
const handleDeleteItem = (context, index) => {
  context.setState({ isDeleteModalVisible: false });
  context.props.deleteItem(index);
};

//===================================================
// Actions
//===================================================
/**
 * show/hide preview modal
 * @param {*} context
 * @param {*} isVisible
 */
 const handlePreviewModalVisible = (context, value) => {
  context.props.setQuizPreviewModalVisible(value.ID);
};

//===================================================
// Redux
//===================================================

//===================================================
// Export
//===================================================
export default QuicPackBread;
