import React from "react";

// components
import Label from "../../atoms/Label";
import Input from "../../atoms/Input";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class InputPassword extends React.Component {
  constructor(props) {
    super(props);
  }

  componentDidMount() {}

  render() {
    const {
      setInputPasswordRef,
      loginFieldErrors,
      credentialsError,
      label,
      value,
      name,
      onChange,
      placeholder,
    } = this.props;
    return (
      <>
        {(loginFieldErrors && loginFieldErrors.invalid_password) ||
        credentialsError ? (
          <Label className="inputErrorLabel">{label} &nbsp;</Label>
        ) : (
          <Label>{label} &nbsp;</Label>
        )}
        <Input
          setInputRef={(inpPass) =>
            setInputPasswordRef && setInputPasswordRef(inpPass)
          }
          typeName="password"
          value={value}
          name={name}
          onChange={onChange}
          placeholder={placeholder}
          inputError={
            credentialsError ||
            (loginFieldErrors && loginFieldErrors.invalid_password)
          }
        />
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
export default InputPassword;
