<template>
    <div>
        <titulo-pagina titulo="Usuarios"></titulo-pagina>

        <b-row>
            <b-col>
                <b-card>
                    <b-row>
                        <b-col lg="6" class="my-1">
                            <b-form-group label="Ordenar" label-cols-sm="2" label-align-sm="left" label-size="sm" label-for="sortBySelect" class="mb-0">
                                <b-input-group size="sm">
                                    <b-form-select v-model="sortBy" id="sortBySelect" :options="sortOptions" class="w-75">
                                        <template v-slot:first>
                                            <option value="">Sin ordenar</option>
                                        </template>
                                    </b-form-select>
                                    <b-form-select v-model="sortDesc" size="sm" :disabled="!sortBy" class="w-25">
                                        <option :value="false">Asc</option>
                                        <option :value="true">Desc</option>
                                    </b-form-select>
                                </b-input-group>
                            </b-form-group>
                        </b-col>

                        <b-col lg="6" class="my-1">
                            <b-form-group label="Orden inicial" label-cols-sm="2" label-align-sm="left" label-size="sm" label-for="initialSortSelect" class="mb-0">
                                <b-form-select v-model="sortDirection" id="initialSortSelect" size="sm">
                                    <option value="asc">Ascendente</option>
                                    <option value="desc">Descendente</option>
                                    <option value="last">Último</option>
                                </b-form-select>
                            </b-form-group>
                        </b-col>

                        <b-col lg="6" class="my-1">
                            <b-form-group label="Búsqueda" label-cols-sm="2" label-align-sm="left" label-size="sm" label-for="filterInput" class="mb-0" >
                            <b-input-group size="sm">
                                <b-form-input v-model="filter" type="search" id="filterInput" placeholder="Escribe para buscar"></b-form-input>
                                <b-input-group-append>
                                    <b-button :disabled="!filter" @click="filter = ''">Limpiar</b-button>
                                </b-input-group-append>
                            </b-input-group>
                            </b-form-group>
                        </b-col>

                        <b-col lg="6" class="my-1">
                            <b-form-group label="Filtrar en" label-cols-sm="2" label-align-sm="left" label-size="sm" class="mb-0">
                                <b-form-checkbox-group v-model="filterOn" class="mt-1">
                                    <b-form-checkbox value="name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Name</b-form-checkbox>
                                    <b-form-checkbox value="age">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Age</b-form-checkbox>
                                    <b-form-checkbox value="isActive">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Active</b-form-checkbox>
                                </b-form-checkbox-group>
                            </b-form-group>
                        </b-col>

                        <b-col lg="6" class="my-1">
                            <b-form-group label="Por página" label-cols-sm="2" label-align-sm="left" label-size="sm" label-for="perPageSelect" class="mb-0">
                                <b-form-select v-model="perPage" id="perPageSelect" size="sm" :options="pageOptions"></b-form-select>
                            </b-form-group>
                        </b-col>

                        <b-col lg="6" class="my-1">
                            <b-pagination v-model="currentPage" :total-rows="totalRows" :per-page="perPage" align="fill" size="sm" class="my-0" ></b-pagination>
                        </b-col>
                    </b-row>

                    <b-table class="my-3" show-empty small striped stacked="md" :items="items" :fields="fields" :current-page="currentPage" :per-page="perPage" :filter="filter" :filterIncludedFields="filterOn" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" :sort-direction="sortDirection" @filtered="onFiltered" >
                        <template v-slot:empty="scope">
                            <center><h5>No hay registros</h5></center>
                        </template>

                        <template v-slot:cell(name)="row">
                            {{ row.value.first }} {{ row.value.last }}
                        </template>

                        <template v-slot:cell(actions)="row">
                            <b-button size="sm" @click="info(row.item, row.index, $event.target)" class="mr-1">Info modal</b-button>
                            <b-button size="sm" @click="row.toggleDetails">{{ row.detailsShowing ? 'Hide' : 'Show' }} Details</b-button>
                        </template>

                        <template v-slot:row-details="row">
                            <b-card>
                                <ul>
                                    <li v-for="(value, key) in row.item" :key="key">{{ key }}: {{ value }}</li>
                                </ul>
                            </b-card>
                        </template>
                    </b-table>
                </b-card>
            </b-col>
        </b-row>
        
        <div class="fixed-bottom mb-5 mr-5">
            <b-button pill variant="success" size="lg" class="pull-right"><i class="ti-plus"></i></b-button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                items: [
                    { isActive: true, age: 40, name: { first: 'Dickerson', last: 'Macdonald' } },
                    { isActive: false, age: 21, name: { first: 'Larsen', last: 'Shaw' } },
                    {
                        isActive: false,
                        age: 9,
                        name: { first: 'Mini', last: 'Navarro' },
                        _rowVariant: 'success'
                    },
                    { isActive: false, age: 89, name: { first: 'Geneva', last: 'Wilson' } },
                    { isActive: true, age: 38, name: { first: 'Jami', last: 'Carney' } },
                    { isActive: false, age: 27, name: { first: 'Essie', last: 'Dunlap' } },
                    { isActive: true, age: 40, name: { first: 'Thor', last: 'Macdonald' } },
                    {
                        isActive: true,
                        age: 87,
                        name: { first: 'Larsen', last: 'Shaw' },
                        _cellVariants: { age: 'danger', isActive: 'warning' }
                    },
                    { isActive: false, age: 26, name: { first: 'Mitzi', last: 'Navarro' } },
                    { isActive: false, age: 22, name: { first: 'Genevieve', last: 'Wilson' } },
                    { isActive: true, age: 38, name: { first: 'John', last: 'Carney' } },
                    { isActive: false, age: 29, name: { first: 'Dick', last: 'Dunlap' } }
                ],
                fields: [
                    { key: 'name', label: 'Person Full name', sortable: true, sortDirection: 'desc' },
                    { key: 'age', label: 'Person age', sortable: true, class: 'text-center' },
                    {
                        key: 'isActive',
                        label: 'is Active',
                        formatter: (value, key, item) => {
                        return value ? 'Yes' : 'No'
                        },
                        sortable: true,
                        sortByFormatted: true,
                        filterByFormatted: true
                    },
                    { key: 'actions', label: 'Actions' }
                ],
                totalRows: 1,
                currentPage: 1,
                perPage: 15,
                pageOptions: [15, 50, 100, 150, 200, 150],
                sortBy: '',
                sortDesc: false,
                sortDirection: 'asc',
                filter: null,
                filterOn: [],
            }
        },
        computed: {
            sortOptions() {
                return this.fields.filter(f => f.sortable).map(f => {
                    return { text: f.label, value: f.key }
                })
            }
        },
        methods: {
            onFiltered(filteredItems) {
                this.totalRows = filteredItems.length
                this.currentPage = 1
            }
        },
        mounted() {
            this.totalRows = this.items.length
        }
    }
</script>