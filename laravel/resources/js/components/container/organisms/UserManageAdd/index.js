import React from "react";

// libs
import { Row, Col, Modal } from "react-bootstrap";

// Components
import Button from "../../../presentational/atoms/Button";
import Image from "../../../presentational/atoms/Image";
import Label from "../../../presentational/atoms/Label";
import InputWithLabel from "../../../presentational/molecules/InputWithLabel";
import { isEmail } from "../../../../common/Validation";

// Images
import CrossIcon from "../../../../assets/CrossIcon.png";

// css
import "./style.css";

//===================================================
// Component
//===================================================

class UserManageAdd extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      id: "",
      userName: "",
      email: "",
      loginFieldErrors: {
        invalid_email: "",
      },
    };
  }

  render() {
    const { isVisible, onHideAddModal } = this.props;
    const { loginFieldErrors } = this.state;
    return (
      <Modal
        show={isVisible}
        onHide={onHideAddModal}
        onClose={() => {}}
        aria-labelledby="contained-modal-title-vcenter"
        centered
        className="organism-add-user-modal-container"
      >
        <Modal.Header className="organism-modal-header">
          <Label>Add User</Label>
          <Image
            url={CrossIcon}
            className="organism-modal-image"
            onClick={onHideAddModal}
          />
        </Modal.Header>
        <Modal.Body className="organism-modal-body">
          <Row className="mb-1">
            <Col>
              <InputWithLabel
                label="Username"
                onChange={(event) =>
                  handleChange({ UserName: event.target.value }, this)
                }
                typeName="text"
              />
            </Col>
          </Row>
          <Row className="mb-1">
            <Col>
              <InputWithLabel
                label="UserID"
                onChange={(event) =>
                  handleChange({ UserID: "ID " + event.target.value }, this)
                }
                typeName="number"
              />
            </Col>
          </Row>
          <Row>
            <Col>
              <InputWithLabel
                setInputEmailRef={(inpEmail) => (this.inpEmail = inpEmail)}
                label="Mail Address"
                onChange={(event) =>
                  handleChange({ MailAddress: event.target.value }, this)
                }
                typeName="email"
                name="MailAddress"
                onChange={(event) => handleInputChange(event, this)}
              />
              {loginFieldErrors.invalid_email && (
                <Label mode="error">{loginFieldErrors.invalid_email}</Label>
              )}
            </Col>
          </Row>
        </Modal.Body>
        <Modal.Footer className="organism-add-modal-footer">
          <Row>
            <Col md={5} xs={5}>
              <Button
                mode="success"
                onClick={() => addUser(this)}
                className="btn-block text-center organism-add-modal-button"
              >
                OK
              </Button>
            </Col>
            <Col md={4} xs={4}>
              <Button
                mode="cancel"
                onClick={onHideAddModal}
                className="btn-block text-center organism-add-modal-button"
              >
                Cancel
              </Button>
            </Col>
          </Row>
        </Modal.Footer>
      </Modal>
    );
  }
}

//===================================================
// functions
//===================================================
/**
 * Input  Add User
 * @param {*} value
 * @param {*} context
 */

const handleChange = (value, context) => {
  context.setState(value);
};

/**
 * Button Add User
 * @param {*} context
 */

const addUser = (context) => {
  const isEmailValidated = isEmail(context.state.MailAddress);
  const { loginFieldErrors } = context.state;
  if (isEmailValidated.isValid) {
    loginFieldErrors.invalid_email = "";
    context.setState({ loginFieldErrors });
  }
  context.inpEmail.focus();
  loginFieldErrors.invalid_email = isEmailValidated.message;
  context.setState({ loginFieldErrors });

  if (!loginFieldErrors.invalid_email) {
    context.props.onHideAddModal();
    context.props.inputUserData(context.state);
  }
};

/**
 * add new user data
 * @param {*} context
 */
const handleAddUser = (context) => {
  const { username, userId, email } = context.state;
  const date = new Date();
  const currentDate =
    date.getFullYear() + "/" + date.getMonth() + "/" + date.getDate();
  const newUser = {
    UserName: username,
    UserID: userId,
    MailAddress: email,
    CreatedDate: currentDate,
  };
  context.props.onAddNewUser(newUser);
  context.props.onHideAddModal();
};

//===================================================
// actions
//===================================================

/**
 * handle inputwithlabel change
 * @param {*} value
 * @param {*} context
 */
const handleInputChange = (event, context) => {
  const { loginFieldErrors } = context.state;
  loginFieldErrors.invalid_email = "";
  context.setState({
    [event.target.name]: event.target.value,
    loginFieldErrors,
  });
};

//===================================================
// redux
//===================================================

//===================================================
// export
//===================================================
export default UserManageAdd;
