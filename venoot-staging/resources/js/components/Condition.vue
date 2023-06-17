<template>
  <v-container
      class="condition"
    >
      <v-layout row>
        <v-flex xs4>
          <v-select
            v-model="condition.variable"
            :items="campos"
            item-text="name"
            item-value="key"
            :label="$t('profile.condition.variable')"
          ></v-select>
        </v-flex>
        <v-flex xs3>
          <v-select
            v-model="condition.operation"
            :items="operations"
            :label="$t('profile.condition.operation')"
          ></v-select>
        </v-flex>
        <v-flex xs4>
          <v-text-field
            v-model="condition.parameter"
            v-validate="'required'"
            :data-vv-as="$t('profile.condition.parameter')"
            data-vv-name="parameter"
            :error-messages="errors.collect('parameter')"
            :label="$t('profile.condition.parameter')"
            required
          >
          </v-text-field>
        </v-flex>
        <v-flex xs1>
          <v-btn
            fab
            small
            color="error"
            @click="$emit('delete')"
          >
            <v-icon>mdi-delete</v-icon>
          </v-btn>
        </v-flex>
      </v-layout>
  </v-container>
</template>

<script>
import { map } from 'lodash'

export default {
  props: [ 'condition', 'campos'],
  data: function () {
    return {
      operations: ['=', '>', '>=', '<', '<=', 'like', ]
    }
  }
}
</script>

<style>

</style>
