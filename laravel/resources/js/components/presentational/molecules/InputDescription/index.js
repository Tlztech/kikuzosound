import React, { createRef } from "react";

//components
import Div from "../../atoms/Div/index";
import Label from "../../atoms/Label/index";
import { Editor } from '@tinymce/tinymce-react';
import { Form } from "react-bootstrap";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class InputDescription extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      mode_class: "",
    };
    this.cmeRef = createRef()
  }

  componentDidMount() {
    switchMode(this);
  }

  render() {
    const { mode_class } = this.state;
    const {
      setInputEmailRef,
      autocomplete,
      onKeyDown,
      min,
      validateError,
      className,
      accept,
    } = this.props;
    return (
      <Div
        className={
          "molecules-inputwithlabel-wrapper" + " " + mode_class + className
        }
      >
        <Label labelError={validateError} mode={this.props.label_mode}>
          {this.props.label}
        </Label>
          <Editor
            id={this.props.name}
            textareaName={this.props.name}
            className="atoms-input"
            onEditorChange={(value,event)=>onChange(value,event,this)}
            value={this.props.value}
            init={{
              toolbar: 'bold italic underline | fontselect fontsizeselect formatselect | forecolor backcolor | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment',
              content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
            }}
          />
      </Div>
    );
  }
}

//===================================================
// Functions
//===================================================

/**
 *
 * @param {*} context
 */
const switchMode = (context) => {
  const { mode } = context.props;

  switch (mode) {
    case "left":
      context.setState({ mode_class: "justify-left-inputwithlabel" });
      break;
    case "right":
      context.setState({ mode_class: "justify-right-inputwithlabel" });
      break;
    default:
      break;
  }
};

//===================================================
// Actions
//===================================================
/**
 * On Change
 * @param {*} context
 */
const onChange= (value,event, context) =>{
  let new_event = {};
  new_event={
    target:{
      value: value,
      name: context.props.name
    }
  }
  context.props.onChange(new_event)
}
//===================================================
// Redux
//===================================================

//===================================================
// Export
//===================================================
export default InputDescription;
