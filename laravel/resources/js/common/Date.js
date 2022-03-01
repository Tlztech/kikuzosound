export const getCurrentDate = () => {
  const date = new Date();
  const currentDate =
    date.getFullYear() + "/" + date.getMonth() + "/" + date.getDate();
  return currentDate;
};

export const oneMonthAgo = () => {
  const result = new Date(
    new Date().getFullYear(),
    new Date().getMonth() - 1,
    new Date().getDate()
  );
  return result;
};
