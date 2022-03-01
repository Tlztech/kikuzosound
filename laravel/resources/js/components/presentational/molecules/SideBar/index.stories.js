import React from "react";
import Sidebar from "./index";
import { BrowserRouter } from "react-router-dom";

export default {
  title: "molecules-Sidebar",
};

export const sidebar = () => (
  <BrowserRouter>
    <Sidebar />
  </BrowserRouter>
);
