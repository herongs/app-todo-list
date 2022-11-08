<template>

  <q-page class="q-pa-md " >
        <div class="text-h4 q-pa-md">To-Do List</div>
        <div class="row justify-center">
          <div class="col-12 q-pa-lg">
            <q-input v-model="newItem" label="Novas Tarefas" @keyup.enter="addItem">
              <template v-slot:after>
                <q-btn round dense flat icon="send" @click="addItem()" />
              </template>
            </q-input>
          </div>
        </div>

        <div v-if="items.length > 0">
        <div
          class="row justify-center "
          v-for="item in items" :key="item.name" @click="item.completed=!item.completed"
           :class="{  'completed': item.completed}" v-ripple
        >
        <q-item-section avatar>
          <q-checkbox @click="updateItem(items)" v-model="items.completed"/>
        </q-item-section>
          <q-item-section class="col-8 q-py-lg">
          <q-item-label>{{item.name}}</q-item-label>
          </q-item-section>
          <q-item-section class="col-auto q-py-md" v-if="items.completed" side>
            <q-btn round dense flat icon="delete" @click.stop="removeItem(items)" />
          </q-item-section>

        </div>
        </div>
        <div v-else>
        <div class="row justify-center q-py-md">
          <div class="col-12">
            <div class="text-h6">Sem itens na Lista</div>
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
  mounted(){
    this.getItem()
  },
  methods: {

    removeItem(items) {
      const index = this.items.indexOf(items)
      this.items.splice(index, 1);
      fetch('http://localhost:8000/api/item/' + items.id, {
        method: 'DELETE',
        credentials: 'same-origin',
        header: {
          'Content-Type' : 'application/json'
        },
      })
      .catch((error) =>{
        console.log(error)
      })
    },
    async updateItem(items){
      await fetch('http://localhost:8000/api/item/' + items.id, {
        method: 'PUT',
        credentials: 'same-origin',
    }
    )
        .then(response => { console.log(response.json()) })
        .catch((error) => {
          console.log(error)
        })

    },
    async addItem() {
      const add = {
          items: {
            name: this.newItem
          }
     }
      console.log('xxx' , JSON.stringify(add))
      await fetch ('http://localhost:8000/api/item/store', {
        method: 'POST',
        credentials: 'same-origin',
        header: {
          'Content-Type' : 'application/json'
        },
        body: JSON.stringify(add)
      })

      .catch((error) => {
        console.log(error)
      })
      this.newItem = ''
      this.getItem()
    },
    getItem(){
      fetch('http://localhost:8000/api/items')
      .then(response => {return response.json()})
      .then(data => {
        console.log(data)
        this.items = data
        for(let i = 0; i < this.items.length;  i++){
          if(this.items[i].completed == 0){
          this.items[i].completed = false
        } else{
          this.items[i].completed = true
        }
        }
      }
      )
    },
  }


})
</script>


<style>
.cocompleted{
  text-decoration: line-through;
  color: red;
}


</style>
