import React from "react";
import {DebounceInput} from 'react-debounce-input';

import Div from "../../atoms/Div/index";
import Input from "../../atoms/Input/index";
import Image from "../../atoms/Image/index";

import icon from "../../../../../../public/search.png";
// css
import "./style.css";

// i18next
import { withTranslation } from "react-i18next";

//===================================================
// Component
//===================================================
class SearchInput extends React.Component {
  constructor(props) {
    super(props);
  }

  componentDidMount() {}

  render() {
    const { onClick, onChange, t , search_input_ref} = this.props;
    return (
      <Div className="molecules-SearchInput-wrapper">
        <DebounceInput
          className= {"molecules-SearchInput molecules-SearchInput-search form-control shadow-none"}
          minLength={0}
          debounceTimeout={1000}
          placeholder={`${t("search")}...`}
          onChange={onChange}
          onClick={onClick} 
          ref={search_input_ref}
        />
        <Image url={icon} mode="search" />
      </Div>
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
export default withTranslation('translation')(SearchInput);
