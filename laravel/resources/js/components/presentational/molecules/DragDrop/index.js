import React from "react";

// library
import i18next from "i18next";
import { DragDropContext, Droppable, Draggable } from "react-beautiful-dnd";

// Components
import Div from "../../atoms/Div";
import Span from "../../atoms/Span";
import Icons from "../../atoms/Icons";
import Label from "../../atoms/Label";

//Images
import { ArrowIcon } from "../../../../assets";

// css
import "./style.css";

//i18n
import { withTranslation } from "react-i18next";

const id2List = {
  droppable1: "examLists",
  droppable2: "examSteps",
};
//===================================================
// Component
//===================================================
class DragDrop extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      tasks: props.tasks || [],
      examSteps: [],
      examList: [],
    };
  }

  render() {
    const { t, tasks, searchKey } = this.props;
    const { examList, examSteps } = this.state;
    const propsExamSteps = [];
    const propsExamList = [];
    tasks.map((item) => {
      if (item) {
        if (item.type == "examLists") propsExamList.push(item);
        else propsExamSteps.push(item);
      }
    });
    const totalExamList = examList.length > 0 ? examList : propsExamList;
    const totalExamSteps = examSteps.length > 0 ? examSteps : propsExamSteps;
    if (this.state.tasks.length == 0) this.state.tasks = this.props.tasks;
    return (
      <Div className="molecules-Drag-wrapper">
        <Label className="col-md-5 molecules-stepofexam">
          {t("stepofexam")+t("required_sign")}
        </Label>
        <DragDropContext onDragEnd={(e) => onDragEnd(e, this)}>
          <Droppable droppableId="droppable1">
            {(provided, snapshot) => (
              <div
                {...provided.droppableProps}
                ref={provided.innerRef}
                className="molecules-examList-container col-md-5"
                style={getListStyle(snapshot.isDraggingOver)}
              >
                {totalExamList.map((item, index) => {
                  const taskName =
                    i18next.language === "en"
                      ? item.taskName_EN
                      : item.taskName;
                  if (
                    !searchKey ||
                    taskName.toLowerCase().includes(searchKey)
                  ) {
                    return (
                      <Draggable
                        key={item.id}
                        draggableId={item.id.toString()}
                        index={index}
                      >
                        {(provided, snapshot) => (
                          <div
                            className="molecules-examList-row"
                            ref={provided.innerRef}
                            {...provided.draggableProps}
                            {...provided.dragHandleProps}
                            style={getItemStyle(
                              snapshot.isDragging,
                              provided.draggableProps.style
                            )}
                          >
                            {i18next.language === "en"
                              ? item.taskName_EN
                              : item.taskName}
                          </div>
                        )}
                      </Draggable>
                    );
                  }
                })}
                {provided.placeholder}
              </div>
            )}
          </Droppable>
          <Div className="molecules-dragDrop-arrow-wrapper">
            <Icons url={ArrowIcon} className="molecules-dragDrop-arrow" />
          </Div>
          <Droppable droppableId="droppable2">
            {(provided, snapshot) => (
              <div
                className="molecules-stepExam-container col-md-5"
                ref={provided.innerRef}
                style={getListStyle(snapshot.isDraggingOver)}
              >
                {totalExamSteps.map((item, index) => (
                  <Draggable
                    key={item.id}
                    draggableId={item.id.toString()}
                    index={index}
                  >
                    {(provided, snapshot) => (
                      <div
                        className="molecules-stepExam-row"
                        ref={provided.innerRef}
                        {...provided.draggableProps}
                        {...provided.dragHandleProps}
                        style={getItemStyle(
                          snapshot.isDragging,
                          provided.draggableProps.style
                        )}
                      >
                        <Span className="molecules-drag-drop-steps sort">
                          ↑↓
                        </Span>
                        <Span className="molecules-drag-drop-steps exam">
                          {index + 1}
                        </Span>
                        {i18next.language === "en"
                          ? item.taskName_EN
                          : item.taskName}
                      </div>
                    )}
                  </Draggable>
                ))}
                {provided.placeholder}
              </div>
            )}
          </Droppable>
        </DragDropContext>
      </Div>
    );
  }
}

//===================================================
// functions
//===================================================
const handleFilterItem = (searchKey, context) => {};
/**
 * Get item lists
 * @param {*} id
 * @param {*} context
 * @param {*} examList
 */
const getList = (id, context, examList) => {
  var list = [];
  if (id2List[id] == "examSteps" && context.state.examSteps.length > 0)
    context.state.examSteps.map((item) => {
      if (item.type == id2List[id]) list.push(item);
    });
  else
    context.state.tasks.map((item) => {
      if (item.type == id2List[id]) list.push(item);
    });
  return list;
};

/**
 * get separate list on drag end
 * @param {*} result
 * @param {*} context
 */
const onDragEnd = (result, context) => {
  const { source, destination } = result;
  // dropped outside the list
  if (!destination) {
    return;
  }
  if (
    source.droppableId === destination.droppableId &&
    id2List[source.droppableId] == "examSteps"
  ) {
    const items = reorder(
      getList(source.droppableId, context, "examSteps"),
      source.index,
      destination.index
    );
    context.setState({ examSteps: items });
    context.props.exam_steps(items);
  } else if (source.droppableId !== destination.droppableId) {
    const theResult = move(
      getList(source.droppableId, context),
      getList(destination.droppableId, context),
      source,
      destination
    );
    context.setState({
      examList: theResult.droppable1,
      examSteps: theResult.droppable2,
    });
    context.props.exam_steps(theResult.droppable2);
  }
};

/**
 * reorder
 *
 * @param {*} list
 * @param {*} startIndex
 * @param {*} endIndex
 */

const reorder = (list, startIndex, endIndex) => {
  const result = Array.from(list);
  const [removed] = result.splice(startIndex, 1);
  result.splice(endIndex, 0, removed);

  return result;
};

/**
 * get Item Style
 *
 * @param {*} isDragging
 * @param {*} draggableStyle
 */
const grid = 8;
const getItemStyle = (isDragging, draggableStyle) => ({
  userSelect: "none",
  padding: grid * 2,
  margin: `0 0 ${grid}px 0`,
  background: isDragging & "#e8e8e8",
  ...draggableStyle,
});

/**
 * get List Style
 *
 * @param {*} isDraggingOver
 */
const getListStyle = (isDraggingOver) => ({
  background: isDraggingOver ? "lightblue" : "lightgrey",
  padding: grid,
  width: 250,
});

/**
 * Moves an item from one list to another list.
 * @param {*} source
 * @param {*} destination
 * @param {*} droppableSource
 * @param {*} droppableDestination
 */
const move = (source, destination, droppableSource, droppableDestination) => {
  const sourceClone = Array.from(source);
  const destClone = Array.from(destination);
  const [removed] = sourceClone.splice(droppableSource.index, 1);
  destClone.splice(droppableDestination.index, 0, removed);
  destClone.map((item) => {
    item.type = id2List[droppableDestination.droppableId];
  });
  const result = {};
  result[droppableSource.droppableId] = sourceClone;
  result[droppableDestination.droppableId] = destClone;
  return result;
};

//===================================================
// actions
//===================================================

//===================================================
// redux
//===================================================

//===================================================
// export
//===================================================
export default withTranslation("translation")(DragDrop);
