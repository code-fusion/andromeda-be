const errorResponse = (message) => {
  return {
    error: true,
    errorMessage: message,
  };
};

const HTTP_CODES = {
  SUCCESS: 200,
  DUPLICATED_RESOURCE: 409,
};

module.exports = {
  errorResponse,
  HTTP_CODES
};
