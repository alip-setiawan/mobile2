// api.js
import axios from "axios";

const API_URL = "http://localhost/ionic/server/api.php";

// AXIOS INSTANCE
const api = axios.create({
  baseURL: API_URL,
  headers: {
    "Content-Type": "application/json",
  },
});

// AUTO TAMBAH TOKEN
api.interceptors.request.use((config) => {
  const token = localStorage.getItem("userToken");
  if (token) {
    // BARIS 18 DIPERBAIKI DENGAN BACKTICK ( ` )
    config.headers["Authorization"] = `Bearer ${token}`; 
  }
  return config;
});

// GET USERS
export const getUsers = async () => {
  const res = await api.get("");
  return res.data;
};

// ADD USER
export const addUser = async (data) => {
  const res = await api.post("", data);
  return res.data;
};

// UPDATE USER
export const updateUser = async (id, data) => {
  // BARIS DIPERBAIKI DENGAN BACKTICK ( ` )
  const res = await api.put(`?id=${id}`, data);
  return res.data;
};

// DELETE USER
export const deleteUser = async (id) => {
  // BARIS DIPERBAIKI DENGAN BACKTICK ( ` )
  const res = await api.delete(`?id=${id}`);
  return res.data;
};