import React from "react";

// libs
import { DragDropContext, Draggable, Droppable } from "react-beautiful-dnd";
import { CircularProgress } from "@material-ui/core";

// components
import EditModal from "../QuizEdit/index";
import DeleteModal from "../DeleteModal/index";
import Image from "../../../presentational/atoms/Image";
import EditDeletePreviewButton from "../../../presentational/molecules/EditDeletePreviewButton";

//images
import { DragNdropIcon } from "../../../../assets";

// style
import "./style.css";

// i18next
import { withTranslation } from "react-i18next";
import i18next from "i18next";

//===================================================
// Component
//===================================================
class QuizListBread extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      deleteItem: null,
      editItem: null,
      isEditModalVisible: false,
      isDeleteModalVisible: false,
      imageHash: Date.now(),
    };
  }

  componentDidUpdate() {
    if (this.props.isUpdated) {
      this.setState({
        imageHash: Date.now(),
      });
    }
  }

  render() {
    const { isEditModalVisible, isDeleteModalVisible, editItem } = this.state;
    const { data } = this.props;
    const table_data = data && data.table_data ? data.table_data : [];
    return (
      <>
        {table_data.length != 0 && !data.isLoading
          ? getTableData(this, table_data)
          : getEmptyResult(this)}

        <EditModal
          isVisible={isEditModalVisible}
          onHideEditModal={() => handleEditModalVisible(this, false, editItem)}
          editItem={editItem}
          libItems={this.props.libItems}
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
  let info = JSON.parse(localStorage.getItem("persist:user"));
  let data = JSON.parse(info.userInfo);
  context.props.updateQuizOrder(items, data.user.id);
};

/**
 * return quiz list table body
 * @param {*} context
 * @param {*} table_data
 */

const getTableData = (context, table_data) => {
  const { userInfo, t, handlePreviewModalVisible } = context.props;
  return (
    <DragDropContext
      onDragEnd={(result) => onDragEnd(result, table_data, context)}
    >
      <Droppable droppableId={"quiz-drag"}>
        {(provided) => (
          <tbody ref={provided.innerRef} {...provided.droppableProps}>
            {table_data &&
              table_data.map((value, index) => {
                return (
                  <Draggable
                    key={value.id}
                    draggableId={value.id.toString()}
                    index={index}
                    isDragDisabled={false}
                  >
                    {(provided) => (
                      <tr
                        {...provided.draggableProps}
                        ref={provided.innerRef}
                        key={value.id}
                      >
                        <td
                          {...provided.dragHandleProps}
                          style={{ width: "40px" }}
                          className="text-center"
                        >
                          <Image mode="drag" url={DragNdropIcon} />
                        </td>
                        <td style={{ width: "80px" }}>{value.id}</td>
                        <td>
                          {i18next.language == "ja"
                            ? value.title
                            : value.title_en}
                        </td>
                        <td className="preview-icon">
                          <Image
                            url={`${
                              value.illustration
                                ? `${value.illustration}?${context.state.imageHash}`
                                : "/img/no_image.png"
                            }`}
                            mode="upload"
                          />
                        </td>
                        <td>{value.library}</td>
                        <td>{value.content}</td>
                        <td>{value.answerOptions}</td>
                        <td>
                          {value.timeLimit == 0
                            ? t("unlimited")
                            : value.timeLimit}
                        </td>
                        <td colSpan="2">
                          {
                         
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
                                  handlePreviewModalVisible(value.id)
                                }
                              />
                            ) : (
                              <EditDeletePreviewButton
                                onPreviewClicked={() =>
                                  handlePreviewModalVisible(value.id)
                                }
                                disableEditDelete={true}
                              />
                            )
                         }
                        </td>
                      </tr>
                    )}
                  </Draggable>
                );
              })}
            {provided.placeholder}
          </tbody>
        )}
      </Droppable>
    </DragDropContext>
  );
};

/**
 * display empty list message
 * @param {*} context
 */
const getEmptyResult = (context) => {
  const { t } = context.props;
  return (
    <tbody>
      <tr className="no-data">
        <td>
          {context.props.data && context.props.data.isLoading ? (
            <CircularProgress />
          ) : (
            t("empty_data")
          )}
        </td>
      </tr>
    </tbody>
  );
};

/**
 * show/hide edit modal
 * @param {*} context
 * @param {*} isVisible
 */
const handleEditModalVisible = (context, isVisible, value) => {
  context.setState({ isEditModalVisible: isVisible, editItem: value });
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

//===================================================
// Redux
//===================================================

//===================================================
// Export
//===================================================
export default withTranslation("translation")(QuizListBread);
