const errorResponse = (message) => {
  return {
    error: true,
    errorMessage: message,
  };
};

const HTTP_CODES = {
  SUCCESS: 200,
  FORBIDDEN: 403,
  NOT_FOUND: 404,
  DUPLICATED_RESOURCE: 409,
  SERVER_ERROR: 500,
};

module.exports = {
  errorResponse,
  HTTP_CODES
};
