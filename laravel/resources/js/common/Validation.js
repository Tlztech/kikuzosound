let isValid = true;
let message = "";

export const isEmail = (email = "") => {
  let regex = /\S+@\S+\.\S+/;
  if (email.length > 0) {
    isValid = regex.test(email);
    if (!isValid) {
      isValid = false;
      message = "invalid_email";
    } else {
      isValid = true;
      message = "";
    }
  } else {
    isValid = false;
    message = "empty_email";
  }
  return {
    isValid: isValid,
    message: message,
  };
};

export const isPasswordValid = (password = "") => {
  if (password.trim().length < 1) {
    isValid = false;
    message = "empty_password";
  }
  return {
    isValid: isValid,
    message: message,
  };
};
