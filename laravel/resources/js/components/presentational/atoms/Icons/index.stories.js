import React from "react";
import { Hamburger, SettingIcon, LogoutIcon, PlayIcon } from "../../../../assets/index";
import Icons from "./index";

export default {
  title: "atoms-Icons",
};

export const SettingsIcon = () => <Icons url={SettingIcon} />;
export const HamburgerIcon = () => <Icons url={Hamburger} />;
export const LogoutIcons = () => <Icons url={LogoutIcon} />;
export const Play_Icon = () => <Icons url={PlayIcon} />;
