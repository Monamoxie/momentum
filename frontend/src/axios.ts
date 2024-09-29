// src/axios.ts
import axios from "axios";

const instance = axios.create({
    baseURL: "http://localhost:81/api/v1/",
});

export default instance;
