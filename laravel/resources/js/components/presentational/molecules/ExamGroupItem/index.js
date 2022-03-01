import React from "react";
import Button from "../../atoms/Button";
import Span from "../../atoms/Span";

//style
import "./style.css";

class ExamGroupItem extends React.Component {
  constructor(props) {
    super(props);
  }

  componentDidMount() {}

  render() {
    const { selected_exam_group, onClick } = this.props;
    return (
      <Span className="select-exam-group">
        {selected_exam_group.map((list, index) => (
          <li key={index}>
            {list.text}
            <Button onClick={() => onClick(index)}>X</Button>
          </li>
        ))}
      </Span>
    );
  }
}

export default ExamGroupItem;
