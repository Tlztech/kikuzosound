import React from "react";

// components
import Label from "../../atoms/Label";
import Input from "../../atoms/Input";

// css
import "./style.css";

//===================================================
// Component
//===================================================
class InputEmail extends React.Component {
  render() {
    const {
      setInputEmailRef,
      loginFieldErrors,
      credentialsError,
      label,
      name,
      onChange,
      placeholder,
      value,
    } = this.props;
    return (
      <>
        {(loginFieldErrors && loginFieldErrors.invalid_email) ||
        credentialsError ? (
          <Label className="inputErrorLabel">{label} &nbsp;</Label>
        ) : (
          <Label> {label} &nbsp; </Label>
        )}
        <Input
          setInputRef={(inpEmail) =>
            setInputEmailRef && setInputEmailRef(inpEmail)
          }
          typeName="email"
          name={name}
          onChange={onChange}
          placeholder={placeholder}
          value={value}
          inputError={
            credentialsError ||
            (loginFieldErrors && loginFieldErrors.invalid_email)
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
export default InputEmail;
