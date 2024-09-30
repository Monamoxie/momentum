// src/axios.ts
import axios from "axios";

const instance = axios.create({
  baseURL: "http://localhost:81/api/v1",
  withCredentials: true,
  headers: {
    "Access-Control-Allow-Origin": "*",
    "Content-Type": "application/json",
  },
});

export default instance;
