import React from "react";
import Input from "./index";

export default {
  title: "atoms-Input"
};

export const text = () => <Input typeName="text" placeholder={"Enter Text"} />;

export const number = () => <Input typeName="number" placeholder={"1"} />;

export const password = () => <Input typeName="password" placeholder={"password"} />;

export const Email = () => <Input typeName="email" placeholder={"test@gmail.com"} />


/**
 * setup redux store for storybook
 */
// import React from 'react';
// import { storiesOf } from '@storybook/react';
// import { Provider } from "react-redux";
// import { createStore } from '../../../../redux/store';
// import Input from './index';

// const store = createStore();

// const withProvider = (story) => (
//   <Provider store={store}>
//     { story() }
//   </Provider>
// )

// storiesOf('molecules-Input', module)
//   .addDecorator(withProvider)
//   .add("Email", () => <Input label="New Email" placeholder="example@gmail.com"></Input>);