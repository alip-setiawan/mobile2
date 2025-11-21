<template>
  <ion-page>
    <ion-header>
      <ion-toolbar color="light">
        <ion-title>Form Siswa</ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-content class="ion-padding">
      <div class="form-container">
        <input v-model="nama" placeholder="Nama" />
        <input v-model="email" placeholder="Email" />
        <button @click="simpanData" class="btn btn-primary">
          {{ idEdit ? "Update" : "Tambah" }}
        </button>
      </div>

      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, i) in dataSiswa" :key="i">
            <td>{{ item.id }}</td>
            <td>{{ item.nama }}</td>
            <td>{{ item.email }}</td>
            <td>
              <button class="btn btn-warning" @click="editData(item)">Edit</button>
              <button class="btn btn-danger" @click="hapusData(item.id)">Hapus</button>
            </td>
          </tr>
        </tbody>
      </table>
    </ion-content>
  </ion-page>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const nama = ref("");
const email = ref("");
const idEdit = ref(null);
const dataSiswa = ref([]);

const API_URL = "http://localhost/projek-siswa/backend/";

const loadData = async () => {
  const res = await axios.get(API_URL + "get_siswa.php");
  dataSiswa.value = res.data;
};

const simpanData = async () => {
  if (!nama.value || !email.value) {
    alert("Isi nama dan email!");
    return;
  }

  if (idEdit.value) {
    await axios.post(API_URL + "update_siswa.php", {
      id: idEdit.value,
      nama: nama.value,
      email: email.value,
    });
    idEdit.value = null;
  } else {
    await axios.post(API_URL + "add_siswa.php", {
      nama: nama.value,
      email: email.value,
    });
  }

  nama.value = "";
  email.value = "";
  loadData();
};

const editData = (item) => {
  idEdit.value = item.id;
  nama.value = item.nama;
  email.value = item.email;
};

const hapusData = async (id) => {
  if (confirm("Yakin ingin hapus data ini?")) {
    await axios.post(API_URL + "delete_siswa.php", { id });
    loadData();
  }
};

onMounted(() => loadData());
</script>

<style>
.form-container {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}

input {
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #ccc;
  width: 200px;
}

button {
  padding: 8px 12px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}

.btn-primary {
  background: #007bff;
  color: white;
}

.btn-warning {
  background: #ffc107;
  color: black;
}

.btn-danger {
  background: #dc3545;
  color: white;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th,
td {
  border: 1px solid #eee;
  padding: 8px;
  text-align: left;
}
</style>