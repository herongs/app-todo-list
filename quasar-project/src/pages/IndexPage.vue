<template>
  <q-page class="">
    <q-input filled v-model="newItem" label="Nova Tarefa">
      <template v-slot:append>
        <q-btn icon="add" @click="addItem(items)" />
      </template>
    </q-input>

    <div v-if="items.length > 0">
      <div
        class="row justify-center"
        v-for="item in items"
        :key="item.name"
        @click="items.completed = !items.completed"
        :class="{ 'completed': items.completed }"
        v-ripple
      >
        <q-item-section avatar class="q-gutter-sm">
          <q-checkbox
            color="red"
            @click="updateItem(items)"
            v-model="items.completed"
          />
        </q-item-section>
        <q-item-section class="col-8 q-py-lg">
          <q-item-label>{{ item.name }}</q-item-label>
        </q-item-section>
        <q-item-section class="col-auto q-py-md" side>
          <q-btn
            round
            flat
            icon="delete"
            color="red"
            @click.stop="removeItem(items)"
          />
        </q-item-section>
      </div>
    </div>
    <div v-else>
      <div class="row  q-p-xl ">
        <div class="col-4 q-pr-xl q-pt-xl">
          <div class="text-h5 q-pl-xl">ADICIONE ITENS</div>
        </div>
      </div>
    </div>
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
    };
  },
  mounted() {
    this.getItem();
  },
  methods: {
    removeItem(items) {
      const index = this.items.indexOf(items);
      this.items.splice(index, 1);
      fetch("http://localhost:8000/api/item/" + items.id, {
        method: "DELETE",
        credentials: "same-origin",
        header: {
          "Content-Type": "application/json",
        },
      }).catch((error) => {
        console.log(error);
      });
    },
    async updateItem(items) {
      await fetch("http://localhost:8000/api/item/" + items.id, {
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
    async addItem() {
      const add = {
        items: {
          name: this.newItem,
        },
      };
      console.log("xxx", this.newItem);
      await fetch("http://localhost:8000/api/item/store", {
        method: "POST",
        credentials: "same-origin",
        header: {
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
    getItem() {
      fetch("http://localhost:8000/api/items")
        .then((response) => {
          return response.json();
        })
        .then((data) => {
          console.log(data);
          this.items = data;
          for (let i = 0; i < this.items.length; i++) {
            if (this.items[i].completed == 0) {
              this.items[i].completed = false;
            } else {
              this.items[i].completed = true;
            }
          }
        });
    },
  },
});
</script>

<style>
.completed {
  text-decoration: line-through;
  color: red;
}
</style>
