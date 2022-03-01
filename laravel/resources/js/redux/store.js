import { createStore as reduxCreateStore, applyMiddleware } from "redux";
import thunk from "redux-thunk";
import logger from "redux-logger";
import { persistStore } from "redux-persist";
import { composeWithDevTools } from "redux-devtools-extension";
import rootReducer from "./modules/reducers";

const middleware =
  process.env.NODE_ENV !== "production"
    ? [thunk, require("redux-immutable-state-invariant").default()]
    : [thunk];

const store = reduxCreateStore(
  rootReducer,
  composeWithDevTools(applyMiddleware(...middleware))
);

export const createStore = () => store;
export const persistor = persistStore(store);

export default store;
