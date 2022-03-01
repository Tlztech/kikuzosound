import React from "react";
import Image from "./index";

const url =
  "http://3sp-exam-admin.localhost/53a749b28de01d4d95d9292c2939bdc7.png";

export default {
  title: "atoms-Image",
};

export const image = () => <Image url={url} mode="upload" />;
