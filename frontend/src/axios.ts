// src/axios.ts
import axios from "axios";

const instance = axios.create({
  baseURL: "http://localhost:81/api/v1",
  headers: {
    "Access-Control-Allow-Origin": "*",
    "Content-Type": "application/json",
  },
});

instance.interceptors.request.use(
  (config) => {
    const token = document.cookie
      .split("; ")
      .find((row) => row.startsWith("XSRF-TOKEN="))
      ?.split("=")[1];

    // Add the X-XSRF-TOKEN to the request headers if the token exists
    if (token) {
      config.headers["X-XSRF-TOKEN"] = decodeURIComponent(token);
    }

    return config;
  },
  (error) => {
    // Handle error
    return Promise.reject(error);
  }
);

export default instance;
