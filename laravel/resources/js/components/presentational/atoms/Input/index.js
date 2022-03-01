import React from "react";

// libs
import { Form } from "react-bootstrap";

//css
import "./style.css";

//i18n
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
class Input extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      class_name: "",
    };
  }

  componentDidMount() {
    this.setState({ class_name: this.props.className || "" });
    switchMode(this);
  }

  render() {
    const { mode_class } = this.state;
    const {
      typeName,
      value,
      name,
      placeholder,
      onFocus,
      onChange,
      onClick,
      setInputRef,
      autocomplete,
      readOnly,
      accept,
      onKeyDown,
      min,
      defaultChecked,
      t,
    } = this.props;
    return typeName == "file" ? (
      <>
        <span
          id={`label_${name}`}
          className={
            "file-select form-control" +
            " " +
            validateError(this.props.inputError)
          }
        >
          <label htmlFor={name} className="choose-btn">
            {t("choose_file")}
          </label>
          <label className="atoms-select-fileName mt-2">
            {t("no_file_chosen")}
          </label>
        </span>

        <input
          id={name}
          style={{ display: "none" }}
          type="file"
          name={name}
          accept={accept}
          onChange={(e) => handleFile(this, e)}
        />
      </>
    ) : (
      <Form.Control
        accept={accept}
        ref={(inp) => setInputRef && setInputRef(inp)}
        type={typeName}
        value={value}
        name={name}
        as={typeName == "textarea" ? "textarea" : "input"}
        className={
          "atoms-input shadow-none" +
          " " +
          this.state.class_name +
          " " +
          validateError(this.props.inputError) +
          " " +
          mode_class
        }
        placeholder={placeholder}
        onFocus={onFocus}
        onClick={onClick}
        autoComplete={autocomplete ? autocomplete : "on"}
        onChange={onChange}
        readOnly={readOnly}
        onKeyDown={onKeyDown}
        min={min}
        defaultChecked={defaultChecked}
      />
    );
  }
}

//===================================================
// Functions
//===================================================
/**
 * get file name
 */
const handleFile = (context, e) => {
  context.props.onChange(e);
  let parentnode = document.querySelector(`#label_${context.props.name}`);
  if (parentnode.childNodes.length > 1)
    parentnode.removeChild(parentnode.lastChild); //file name already appended
  let filename_node = document.createElement("Label");
  filename_node.className = "atoms-select-fileName mt-2";
  filename_node.append(e.target.files[0].name);
  parentnode.appendChild(filename_node);
};

/**
 * switch button modes
 */
const switchMode = (context) => {
  const { mode } = context.props;
  switch (mode) {
    case "":
    case "search":
      context.setState({ mode_class: "atoms-input-search" });
      break;
    default:
      break;
  }
};

/**
 * get validation error true/false
 * @param {*} mode
 * @param {*} context
 */
const validateError = (value) => {
  if (value) {
    return "atoms-input-validate-error";
  } else {
    return null;
  }
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
export default withTranslation("translation")(Input);
