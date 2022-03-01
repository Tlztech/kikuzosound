import React from "react";

// components
import Div from "../../atoms/Div/index";
import Switch from '@material-ui/core/Switch';
import { Row, Col, Modal } from "react-bootstrap";
import Input from "../../atoms/Input";
import Label from "../../atoms/Label";

// libs
import { connect } from "react-redux";
import {CopyToClipboard} from 'react-copy-to-clipboard';

// icons
import OpenInBrowserIcon from "@material-ui/icons/OpenInBrowser";
import LinkRounded from "@material-ui/icons/LinkRounded";

// style
import "./style.css";

// i18next
import i18next from "i18next";

// redux
import {
  updateAusculaideUrl,
} from "../../../../redux/modules/actions/AusculaideLibraryAction";
//===================================================
// Component
//===================================================
class IpaxUrlSwitch extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      showModal: false,
      checked: this.props.data.moodle_url ? true : false,
      url : this.props.data.moodle_url,
      copied: false
    };
  }
  render() {
    return (
      <React.Fragment>
       <Switch
          checked={this.state.checked}
          onChange={(event,value)=>handleSwitch(event,value,this)}
          color="primary"
        />
      {
        (this.state.checked) &&
        <OpenInBrowserIcon onClick={()=>toggleModal(this)}/>
      }
      <Modal
        onHide={() => toggleModal(this)}
        show={this.state.showModal}
        onClose={() => {}}
        size="lg"
        centered
      >
        <Modal.Body className="molecules-modal-bodyWrapper">
          <Div>
            <LinkRounded color="inherit" fontSize="medium" className ={"molecules-icon-link"}/>
            <Label
            className = {"molecules-label-copyURLTitle"}
            >
              Url
            </Label>
          </Div>
          <Div>
            <Input
              className = {"molecules-input-moodleURL"}
              typeName="text"
              value={this.state.url || ""}
            />
            <CopyToClipboard text={this.state.url}
              onCopy={() => this.setState({copied: true})}>
                <Label className = {"molecules-label-copyURL"}>COPY URL</Label>
            </CopyToClipboard>
          </Div>
          <Div>
            {
              this.state.copied &&
              <React.Fragment>
              {getCopySuccessMsg(this)}
              <Label mode={"success"}>Url Copied.</Label>
              </React.Fragment>
            }
          </Div>
        </Modal.Body>
      </Modal>

      </React.Fragment>
    );
  }
}

//===================================================
// Functions
//===================================================
/**
 * get toggle modal
 * @param {*} context
 */
const toggleModal = (context) => {
  context.setState({
    showModal : !context.state.showModal
  })
};

//===================================================
// Actions
//===================================================
/**
 * on getCopySuccessMsg
 * @param {*} context
 */
const getCopySuccessMsg = (context) =>{
  setTimeout(() => {
    context.setState({ copied: false });
  }, 5000)
}
/**
 * on handle switch
 * @param {*} context
 */
const handleSwitch = async(event, value, context) =>{
  const { userToken, userInfo } = context.props;
  const SITEURL = window.location.protocol + "//" + window.location.hostname;
  var version = new Date(context.props.data.created_at);
  let url = '';
  if(value){
    url = SITEURL + "/ipax_url/"+context.props.data.ID+"?v="+Date.parse(version)+"&asset_ver="+Date.parse(version);
  }
  await context.props.updateAusculaideUrl(
    url,
    userToken,
    context.props.data.ID
  );
  context.setState({
    checked : value,
    url : url
  })
}
//===================================================
// Redux
//===================================================
const mapStateToProps = (state) => {
  return {
    userToken: state.auth.userInfo && state.auth.userInfo.authorization,
    userInfo: state.auth.userInfo && state.auth.userInfo.user,
  };
};

//===================================================
// Export
//===================================================
export default connect(mapStateToProps, {updateAusculaideUrl})(IpaxUrlSwitch);
