import React from "react";

// components
import Div from "../../atoms/Div";
import Span from "../../atoms/Span";
import Label from "../../atoms/Label";
import Image from "../../atoms/Image";


// style
import "./style.css";

//===================================================
// Component
//===================================================
class TextWithIcon extends React.Component {
  render() {
    const { title, textAlign } = this.props;
    return (
      <Div
        className="molecules-textWithIcon-wrapper"
        onClick={(e) => sortOrder(e,this)}
      >
        <Label mode={textAlign}>{title}</Label>
        <Span className = "arrows">
          <Div className = "up-arrow"></Div>
          <Div className = "down-arrow"></Div>
        </Span>
      </Div>
    );
  }
}

//===================================================
// Functions
//===================================================
const sortOrder = (e,context) => {
  const {onClick , index} = context.props
  const up_arrow = document.querySelectorAll('.up-arrow')
  const down_arrow = document.querySelectorAll('.down-arrow')
  
 up_arrow.forEach(arrow => arrow != up_arrow[index] && arrow.classList.remove('active'))
 down_arrow.forEach(arrow => arrow != down_arrow[index] && arrow.classList.remove('active'))
  
  onClick && onClick()
  if(up_arrow[index].classList.contains('active')) {
    up_arrow[index].classList.remove('active')
    down_arrow[index].classList.add('active')
  } else{
    down_arrow[index].classList.remove('active')
    up_arrow[index].classList.add('active')
  }
}
//===================================================
// Actions
//===================================================

//===================================================
// Redux
//===================================================

//===================================================
// Export
//===================================================
export default TextWithIcon;
