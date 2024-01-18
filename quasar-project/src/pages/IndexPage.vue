<template>
  <q-page padding class="flex justify-center">
    <q-card class="card bg-grey-3 q-pa-md">
      <div class="text-h5 justify-center flex q-py-md">TAREFAS</div>

      <div class="q-pt-md row">
        <q-input
          dense
          outlined
          v-model="newItem"
          label="Nova Tarefa"
          class="col-10"
        />

        <div class="col-2">
          <q-btn @click="addItem(items)" color="green" class="q-ml-md">
            Adicionar
          </q-btn>
        </div>
      </div>

      <div v-if="items.length > 0">
        <div
          class="row"
          v-for="item in items"
          :key="item.name"
          @click="items.completed = !items.completed"
        >
          <q-item-section avatar class="col-1">
            <q-checkbox
              color="red"
              @click="updateItem(item)"
              v-model="item.completed"
            />
          </q-item-section>
          <q-item-section class="col-9 q-py-lg">
            <q-item-label
              v-if="item.edit == false"
              :class="{ completed: item.completed }"
              class="text-body1"
              >{{ item.name }}</q-item-label
            >

            <q-input v-model="item.name" dense v-if="item.edit == true">
            </q-input>
          </q-item-section>
          <q-item-section class="col-2" side>
            <div class="flex items-center">
              <q-icon
                v-if="item.edit == false"
                name="edit"
                size="sm"
                class="q-pr-md"
                color="blue"
                @click="(item.edit = !item.edit), (editModel = item.name)"
              ></q-icon>
              <q-icon
                v-if="item.edit == true"
                name="check"
                size="sm"
                class="q-pr-md"
                color="green"
                @click="(item.edit = !item.edit), updateModel(item)"
              />

              <q-btn @click.stop="removeItem(item)" color="red" class="q-mr-sm">
                <q-icon name="delete"></q-icon>
              </q-btn>
            </div>
          </q-item-section>
        </div>
      </div>
    </q-card>
  </q-page>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  name: "IndexPage",
  data() {
    return {
      newItem: "",
      items: [],
      edit: false,
    };
  },
  mounted() {
    this.getItem();
  },
  methods: {
    async removeItem(item) {
      await fetch("http://localhost:8000/api/item/" + item.id, {
        method: "DELETE",
        credentials: "same-origin",
        headers: {
          "Content-Type": "application/json",
        },
      })
        .then(() => {
          this.getItem();
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async updateItem(item) {
      await fetch("http://localhost:8000/api/item/" + item.id, {
        method: "PUT",
        credentials: "same-origin",
      })
        .then((response) => {
          console.log(response.json());
        })
        .catch((error) => {
          console.log(error);
        });
    },

    async updateModel(item) {
      const edit = {
        name: item.name,
      };

      await fetch("http://localhost:8000/api/item/" + item.id, {
        method: "PUT",
        credentials: "same-origin",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(edit),
      })
        .then(() => {
          this.getItem();
        })
        .catch((error) => {
          console.log(error);
        });
    },

    async addItem() {
      const add = {
        name: this.newItem,
      };

      await fetch("http://localhost:8000/api/item", {
        method: "POST",
        credentials: "same-origin",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(add),
      })
        .then((response) => {
          console.log(response.json());
        })
        .catch((error) => {
          console.log(error);
        });
      this.newItem = "";
      this.getItem();
    },
    async getItem() {
      await fetch("http://localhost:8000/api/items")
        .then((response) => {
          return response.json();
        })
        .then((data) => {
          this.items = data;
          for (let i = 0; i < this.items.length; i++) {
            if (this.items[i].completed == 0) {
              this.items[i].completed = false;
            } else {
              this.items[i].completed = true;
            }
            this.items[i].edit = false;
          }
        });
    },
  },
});
</script>

<style scoped>
.completed {
  text-decoration: line-through;
  color: red;
}

.card {
  width: 800px !important;
}
</style>
