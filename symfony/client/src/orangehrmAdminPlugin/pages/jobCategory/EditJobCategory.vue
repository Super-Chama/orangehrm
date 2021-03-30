<!--
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */
 -->

<template>
  <div class="orangehrm-background-container">
    <div class="orangehrm-card-container">
      <oxd-text tag="h6">Edit Job Category</oxd-text>

      <oxd-divider />

      <oxd-form @submitValid="onSave">
        <oxd-form-row>
          <oxd-input-field
            label="Job Category Name"
            v-model="category.name"
            :rules="rules.name"
          />
        </oxd-form-row>

        <oxd-divider />

        <oxd-form-actions>
          <oxd-button
            type="button"
            displayType="ghost"
            label="Cancel"
            @click="onCancel"
          />
          <oxd-button
            class="orangehrm-left-space"
            displayType="secondary"
            label="Update"
            type="submit"
          />
        </oxd-form-actions>
      </oxd-form>
    </div>
  </div>
</template>

<script>
import useUpdate from '@/core/util/composable/useUpdate';
import useRules from '@/core/util/composable/useRules';

export default {
  props: {
    jobCategoryId: {
      type: Number,
      required: true,
    },
  },
  setup(props) {
    const category = {
      id: '',
      name: '',
    };
    const _rules = {
      name: ['required', 'max|60'],
    };
    const {dto, isLoading, execQueryUpdate} = useUpdate(
      props.jobCategoryId,
      'api/v1/admin/job-categories',
      category,
    );
    const {rules} = useRules(_rules);
    return {
      isLoading,
      execQueryUpdate,
      category: dto,
      rules,
    };
  },
  methods: {
    onSave() {
      this.execQueryUpdate()
        .then(() => {
          // go back
          this.onCancel();
        })
        .catch(error => {
          console.log(error);
        });
    },
    onCancel() {
      history.go(-1);
    },
  },
};
</script>
